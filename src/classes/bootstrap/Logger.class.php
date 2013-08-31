<?php
/**
* frontend interface of loggers
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Logger extends Charcoal_Object
{
	static $loggers = array();
	static $buffer = array();
	static $profile_level;
	static $log_enabled;
	static $tag_filters;
	static $output_loggers;
	static $log_no_buffer;

	static $echo_logger;

	/**
	 * initialize logger
	 */
	public static function init()
	{
		self::$profile_level  = Charcoal_Profile::getString( s('LOG_LEVEL') );
		self::$log_enabled    = Charcoal_Profile::getBoolean( s('LOG_ENABLED'), b(TRUE) )->isTrue();
		self::$tag_filters    = Charcoal_Profile::getArray( s('LOG_TAG_FILTERS') );
		self::$output_loggers = Charcoal_Profile::getArray( s('LOG_LOGGERS') );
		self::$log_no_buffer  = Charcoal_Profile::getBoolean( s('LOG_NO_BUFFER'), b(TRUE) )->isTrue();

		self::$echo_logger = Charcoal_Framework::testEchoFlag( i(Charcoal_EnumEchoFlag::ECHO_LOGGER) );

		if ( self::$echo_logger ){
			echo "LOG_LEVEL:" . self::$profile_level . eol();
			echo "LOG_ENABLED:" . self::$log_enabled . eol();
			echo "LOG_TAG_FILTERS:" . self::$tag_filters . eol();
			echo "LOG_LOGGERS:" . self::$output_loggers . eol();
			echo "LOG_NO_BUFFER:" . self::$log_no_buffer . eol();
		}
	}

	/**
	 * clear loggers
	 */
	public static function clear()
	{
		self::$loggers = NULL;
	}

	/**
	 * flush write buffer
	 */
	public static function flush()
	{
		if ( !self::$log_enabled ){
			if ( self::$echo_logger ){
				echo "LOG_ENABLED: OFF" . eol();
			}

			return;
		}

		if ( self::$buffer === NULL ){
			if ( self::$echo_logger ){
				echo "Buffer is empty." . eol();
			}

			return;
		}

		foreach( self::$buffer as $msg )
		{
			self::flushMessage( $msg );
		}

		self::$buffer = NULL;
	}

	/**
	 * flush a message
	 */
	public static function flushMessage( Charcoal_LogMessage $msg )
	{
		// 対象ロガーに対してのみ出力
		$output_loggers = self::$output_loggers ? uv(self::$output_loggers) : array();
		$output_loggers = array_flip($output_loggers);

		$level        = $msg->getLevel();
		$logger_names = $msg->getLoggerNames();

		if ( !self::$profile_level ){
			// LOG_LEVELが設定されていない場合は、WARNING以上出力
			self::$profile_level = s('W');
		}

		// プロファイルに設定したレベル以下ならば出力しない
		$cmp = self::_compareLogLevel($level,self::$profile_level);
		if ( $cmp > 0 ){
			return;
		}

		// タグフィルタが設定されている場合、マッチしないログは無視
		if ( self::$tag_filters && count(self::$tag_filters) > 0 ){
			if ( !self::$tag_filters->contains($msg->getTag()) ){
				return;
			}
		}

		foreach( $logger_names as $key )
		{
			// 登録されていて、かつプロファイルにエントリがあるログだけに出力する
			if ( isset(self::$loggers[$key]) && isset($output_loggers[$key]) ){
				$logger = self::$loggers[ $key ];
				if ( self::$echo_logger ){
					echo get_class($logger) . "#writeln()" . eol();
				}
				$logger->writeln( $msg );
			}
		}
	}

	/**
	 * shutdown all loggers
	 */
	public static function terminate()
	{
		if ( self::$echo_logger ){
			echo __CLASS__ . "#terminate()" . eol();
		}

		self::flush();

		$loggers = self::$loggers;

		foreach( $loggers as $logger )
		{
			// output footer
			$logger->writeFooter();

			// terminate logger
			$logger->terminate();
		}
	}

	/*
	 *	check if a logger is registered
	 */
	public static function isRegistered( Charcoal_String $key )
	{
		$key = $key->getValue();
		return isset(self::$loggers[$key]);
	}

	/*
	 *	register a logger
	 */
	public static function register( Charcoal_String $key, Charcoal_ILogger $logger )
	{
		$key = $key->getValue();

		// set a logger to array
		self::$loggers[$key] = $logger;

		// output header
		$logger->writeHeader();
	}

	/*
	 * write one message
	 */
	public static function writeln( Charcoal_String $target, Charcoal_String $tag, Charcoal_String $message, Charcoal_Integer $echo_flag = NULL )
	{
		try{
			// get caller
			list( $file, $line ) = Charcoal_System::caller(1);
			
			// force echo
			if ( $echo_flag && Charcoal_Framework::testEchoFlag($echo_flag) ){
				echo "$message    $file($line)" . eol();
			}

			// get log level and logger names
			list( $level, $logger_names ) = self::_getLevelAndTargetList( $target );

			// create log message object
			$msg = new Charcoal_LogMessage( s($level), s($tag), s($message), s($file), i($line), v($logger_names) );

			// get LOG_NO_BUFFER flag
			$log_no_buffer = self::$log_no_buffer;

			if ( $log_no_buffer ){
				// flush immediately
				self::flushMessage( $msg );
			}
			else{
				// store log message to buffer
				self::$buffer[] = $msg;
			}
		}
		catch ( Exception $e )
		{
			echo '<textarea style="width:100%; height:300px">';
			echo print_r($e,true);
			echo '</textarea>';
			exit;
		}
	}

	/**
	 *
	 * compare log levels
	 *
	 */
	private static function _compareLogLevel( $lv1, $lv2 )
	{
		static $defs;

		if ( !$defs ){
			$defs = array(
					'F' => 100,
					'E' => 200,
					'W' => 300,
					'D' => 400,
					'I' => 500,
					'T' => 600,
				);
		}

		$lv1 = us($lv1);
		$lv2 = us($lv2);

		$lv1 = isset($defs[$lv1]) ? $defs[$lv1] : self::LOGLEVEL_INFO;
		$lv2 = isset($defs[$lv2]) ? $defs[$lv2] : self::LOGLEVEL_INFO;

		return ($lv1 - $lv2);
	}

	/*
	 *	targetからログレベルとロガー名を取得
	 *
	 *	target文字列のフォーマット：
	 *
	 *  [ログレベル]:[ロガー名],[ロガー名],...
	 *	ex) "I:app,debug,sql"
	 */
	private static function _getLevelAndTargetList( Charcoal_String $target )
	{
		// コロンで分割
		list($level,$logger_names) = explode( ":" , $target->getValue() );

		// ロガー名リスト
		$logger_names = explode( "," , $logger_names );

		// スペースを削除
		foreach( $logger_names as $key => $logger ){
			$logger_names[$key] = trim($logger);
		}

		return array( s($level), v($logger_names) );
	}

}


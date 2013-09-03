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
	static private $loggers;
	static private $buffer;

	/**
	 * flush write buffer
	 */
	public static function flush()
	{
		if ( self::$buffer === NULL ){
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
		$log_enabled    = Charcoal_Profile::getBoolean( s('LOG_ENABLED') );

		if ( !$log_enabled || $log_enabled->isFalse() ){
			return;
		}

		$output_loggers = Charcoal_Profile::getArray( s('LOG_LOGGERS'), v(array()) );

		// プロファイルに設定されているロガーを取得
		$logger_names = Charcoal_Profile::getArray( s('LOG_LOGGERS') );
//		log_debug( 'system, debug',"logger_names: $logger_names", 'framework' );

		// ロガーの登録
		if ( !self::$loggers )
		{
			if ( $logger_names ){
				foreach( $logger_names as $logger_name ){
					$registerd = self::isRegistered( s($logger_name) );
					if ( !$registerd ){
						$logger = Charcoal_Factory::createObject( s($logger_name), s('logger'), v(array()), s('Charcoal_ILogger') );
						self::register( s($logger_name), $logger );
					}
					else{
						log_warning( "system,debug,error", "Logger[$logger_name] is already registered!" );
					}
				}
			}
			else{
				self::$loggers = array();
			}
		}

		// 対象ロガーに対してのみ出力
		$output_loggers = $output_loggers->flip();

		$level        = $msg->getLevel();
		$logger_names = $msg->getLoggerNames();

		// プロファイルに設定したレベル以下ならば出力しない
		$log_level = Charcoal_Profile::getString( s('LOG_LEVEL'), s('W') );
		$cmp = self::_compareLogLevel($level,$log_level);
		if ( $cmp > 0 ){
			return;
		}

		// タグフィルタが設定されている場合、マッチしないログは無視
		$tag_filters = Charcoal_Profile::getArray( s('LOG_TAG_FILTERS') );
		if ( $tag_filters && !$tag_filters->isEmpty() ){
			if ( !$tag_filters->contains($msg->getTag()) ){
				return;
			}
		}

		foreach( $logger_names as $key )
		{
			// 登録されていて、かつプロファイルにエントリがあるログだけに出力する
			if ( isset(self::$loggers[$key]) && isset($output_loggers[$key]) ){
				$logger = self::$loggers[ $key ];
				$logger->writeln( $msg );
			}
		}
	}

	/**
	 * shutdown all loggers
	 */
	public static function terminate()
	{
		self::flush();

		if ( self::$loggers )
		{
			foreach( self::$loggers as $logger )
			{
				// output footer
				$logger->writeFooter();

				// terminate logger
				$logger->terminate();
			}

			self::$loggers = NULL;
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
	public static function writeln( Charcoal_String $target, Charcoal_String $message, Charcoal_String $tag = NULL )
	{
		try{
			// get caller
			list( $file, $line ) = Charcoal_System::caller(1);
			
			// get log level and logger names
			list( $level, $logger_names ) = self::_getLevelAndTargetList( $target );

			// create log message object
			$msg = new Charcoal_LogMessage( s($level), s($tag), s($message), s($file), i($line), v($logger_names) );

			// get LOG_NO_BUFFER flag
			$log_no_buffer  = Charcoal_Profile::getBoolean( s('LOG_NO_BUFFER'), b(FALSE) );

			if ( $log_no_buffer && $log_no_buffer->isTrue() ){
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


<?php
/**
* frontend interface of loggers
*
* PHP version 5
*
* @package    class.bootstrap
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_LoggerList extends Charcoal_Object
{
	const LOGLEVEL_FATAL    = 100;
	const LOGLEVEL_ERROR    = 200;
	const LOGLEVEL_WARNING  = 300;
	const LOGLEVEL_DEBUG    = 400;
	const LOGLEVEL_INFO     = 500;
	const LOGLEVEL_TRACE    = 600;

	private $loggers;
	private $buffer;
	private $sandbox;
	private $options;
	private $init;
	private $log_enabled;
	private $log_level;
	private $log_no_buffer;
	private $log_tag_filters;
	private $log_loggers;

	/**
	 *  Constructor
	 */
	public function __construct( $sandbox )
	{
//		Charcoal_ParamTrait::checkSandbox( 1, $sandbox );

		$this->sandbox = $sandbox;

		parent::__construct();

	}

	/**
	 * initialize exception handler list
	 */
	public function init()
	{
		// if this object is already initialized, do nothing
		if ( $this->init ){
			return TRUE;
		}

		// if our sandbox is not loaded, do nothing
		if ( !$this->sandbox->isLoaded() ){
			return FALSE;
		}

		// read initialization options from sandbox profile
		$this->log_enabled      = ub( $this->sandbox->getProfile()->getBoolean( 'LOG_ENABLED', FALSE ) );
		$this->log_level        = us( $this->sandbox->getProfile()->getString( 'LOG_LEVEL', 'W' ) );
		$this->log_no_buffer    = ub( $this->sandbox->getProfile()->getBoolean( 'LOG_NO_BUFFER', FALSE ) );
		$this->log_tag_filters  = uv( $this->sandbox->getProfile()->getArray( 'LOG_TAG_FILTERS', array() ) );
		$this->log_loggers      = uv( $this->sandbox->getProfile()->getArray( 'LOG_LOGGERS', array() ) );

		$this->loggers = array();

		// create loggers on demand
		if ( $this->log_loggers ){
			foreach( $this->log_loggers as $logger_name ){
				if ( strlen($logger_name) === 0 )    continue;

				if ( !isset($this->loggers[$logger_name]) ){
					$logger = $this->sandbox->createObject( $logger_name, 'logger', array(), 'Charcoal_ILogger' );
					self::register( $logger_name, $logger );
				}
				else{
					log_warning( "system,debug,error", "Logger[$logger_name] is already registered!" );
				}
			}
		}

		$this->init = TRUE;

		return TRUE;
	}

	/**
	 * override by procedure settings
	 */
	public function overrideByProcedure( Charcoal_IProcedure $procedure )
	{
		$log_enabled = $procedure->isLoggerEnabled();
		$log_level = $procedure->getLogLevel();
		$log_loggers = $procedure->getLoggers();

		if ( $log_enabled !== NULL ){
			$this->log_enabled = $log_enabled;
		}
		if ( $log_level !== NULL ){
			$this->log_level = $log_level;
		}
		if ( $log_loggers !== NULL ){
			$this->log_loggers = $log_loggers;
			
			// create loggers on demand
			foreach( $this->log_loggers as $logger_name ){
				if ( strlen($logger_name) === 0 )    continue;

				if ( !isset($this->loggers[$logger_name]) ){
					$logger = $this->sandbox->createObject( $logger_name, 'logger', array(), 'Charcoal_ILogger' );
					self::register( $logger_name, $logger );
				}
			}

		}
	} 

	/**
	 * set log level
	 */
	public function setLogLevel( $log_level )
	{
		$old_level = $this->log_level;
		$this->log_level = $log_level;
		return $old_level;
	}

	/**
	 * get log level
	 */
	public function getLogLevel()
	{
		return $this->log_level;
	}

	/**
	 * flush write buffer
	 */
	public function flush()
	{
		if ( $this->buffer === NULL ){
			return;
		}

		foreach( $this->buffer as $msg )
		{
			self::flushMessage( $msg );
		}

		$this->buffer = NULL;
	}

	/**
	 * flush a message
	 * 
	 * @param Charcoal_LogMessage $msg    message object to flush
	 */
	public function flushMessage( $msg )
	{
//		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_LogMessage', $msg );

		if ( !$this->init() ){
			return;
		}

		if ( !$this->log_enabled ){
			return;
		}

		// 対象ロガーに対してのみ出力
		$output_loggers = array_flip( $this->log_loggers );

		$level        = $msg->getLevel();
		$logger_names = $msg->getLoggerNames();

		// プロファイルに設定したレベル以下ならば出力しない
		$cmp = self::_compareLogLevel($level,$this->log_level);
		if ( $cmp > 0 ){
			return;
		}

		// タグフィルタが設定されている場合、マッチしないログは無視
		$log_tag_filters = $this->log_tag_filters;
		if ( is_array($log_tag_filters) && !empty($log_tag_filters) ){
			if ( !in_array( $msg->getTag(), $log_tag_filters ) ){
				return;
			}
		}

		foreach( uv($logger_names) as $key )
		{
			// 登録されていて、かつプロファイルにエントリがあるログだけに出力する
			if ( isset($this->loggers[$key]) && isset($output_loggers[$key]) ){
				$logger = $this->loggers[ $key ];
				$logger->writeln( $msg );
			}
		}
	}

	/**
	 * shutdown all loggers
	 */
	public function terminate()
	{
		self::flush();

		if ( $this->loggers )
		{
			foreach( $this->loggers as $logger )
			{
				// output footer
				$logger->writeFooter();

				// terminate logger
				$logger->terminate();
			}

			$this->loggers = NULL;
		}
	}

	/*
	 *	check if a logger is registered
	 * 
	 * @param Charcoal_String $key    string key to check
	 */
	public function isRegistered( $key )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );

		$key = us( $key );
		return isset($this->loggers[$key]);
	}

	/*
	 *	register a logger
	 * 
	 * @param Charcoal_String $key        string key to register
	 * @param Charcoal_ILogger $logger    logger objecty to be registered
	 */
	public function register( $key, $logger )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );
//		Charcoal_ParamTrait::checkImplements( 1, 'Charcoal_ILogger', $logger );
//
		$key = us( $key );

		// set a logger to array
		$this->loggers[$key] = $logger;

		// output header
		$logger->writeHeader();
	}

	/*
	 * write one message
	 * 
	 * @param Charcoal_String $target     log target
	 * @param Charcoal_String $message    log message to write
	 * @param Charcoal_String $tag        tag string(optional)
	 */
	public function writeLog( $target, $message, $tag = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $target );
//		Charcoal_ParamTrait::checkString( 2, $message );
//		Charcoal_ParamTrait::checkString( 3, $tag, TRUE );

		try{
			// get caller
			list( $file, $line ) = Charcoal_System::caller(2);
			
			// get log level and logger names
			list( $level, $logger_names ) = self::_getLevelAndTargetList( $target );

			// create log message object
			$msg = new Charcoal_LogMessage( $level, $message, $tag, $file, $line, $logger_names );

			// get LOG_NO_BUFFER flag
			if ( $this->init && $this->log_no_buffer ){
				// flush immediately
				$this->buffer[] = $msg;
				self::flush();
			}
			else{
				// store log message to buffer
				$this->buffer[] = $msg;
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
					'F' => self::LOGLEVEL_FATAL,
					'E' => self::LOGLEVEL_ERROR,
					'W' => self::LOGLEVEL_WARNING,
					'D' => self::LOGLEVEL_DEBUG,
					'I' => self::LOGLEVEL_INFO,
					'T' => self::LOGLEVEL_TRACE,
				);
		}

		$lv1 = us($lv1);
		$lv2 = us($lv2);

		$lv1 = isset($defs[$lv1]) ? $defs[$lv1] : self::LOGLEVEL_INFO;
		$lv2 = isset($defs[$lv2]) ? $defs[$lv2] : self::LOGLEVEL_INFO;

		return ($lv1 - $lv2);
	}

	/*
	 *	get logger names and levels
	 *
	 *	format of parameter '$target':
	 *  [log_level]:[logger_name1],[logger_name2],...
	 *
	 *	ex) "I:app,D:debug,sql"
	 */
	private static function _getLevelAndTargetList( $target )
	{
//		Charcoal_ParamTrait::checkString( 1, $target );

		// コロンで分割
		list($level,$logger_names) = explode( ':' , $target );

		// ロガー名リスト
		$logger_names = explode( ',' , $logger_names );

		// スペースを削除
		$logger_names = array_map( 'trim', $logger_names );

		return array( $level, $logger_names );
	}

}


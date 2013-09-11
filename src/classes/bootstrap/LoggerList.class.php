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

class Charcoal_LoggerList extends Charcoal_Object
{
	private $loggers;
	private $buffer;
	private $sandbox;
	private $options;
	private $init;

	/**
	 *  Constructor
	 */
	public function __construct( $sandbox )
	{
		Charcoal_ParamTrait::checkSandbox( 1, $sandbox );

		$this->sandbox = $sandbox;

		parent::__construct();
	}

	/**
	 * initialize exception handler list
	 */
	private function init()
	{
		if ( $this->init )	return;

		$this->options['LOG_ENABLED']      = $this->sandbox->getProfile()->getBoolean( 'LOG_ENABLED', FALSE );
		$this->options['LOG_LEVEL']        = $this->sandbox->getProfile()->getString( 'LOG_LEVEL', 'W' );
		$this->options['LOG_NO_BUFFER']    = $this->sandbox->getProfile()->getBoolean( 'LOG_NO_BUFFER', FALSE );
		$this->options['LOG_TAG_FILTERS']  = $this->sandbox->getProfile()->getArray( 'LOG_TAG_FILTERS', array() );
		$this->options['LOG_LOGGERS']      = $this->sandbox->getProfile()->getArray( 'LOG_LOGGERS', array() );

		$this->loggers = array();

		if ( $this->options['LOG_LOGGERS'] ){
			foreach( $this->options['LOG_LOGGERS'] as $logger_name ){
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
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_LogMessage', $msg );

		$this->init();

		if ( $this->options['LOG_ENABLED'] === FALSE ){
			return;
		}

		// 対象ロガーに対してのみ出力
		$output_loggers = array_flip( $this->options['LOG_LOGGERS'] );

		$level        = $msg->getLevel();
		$logger_names = $msg->getLoggerNames();

		// プロファイルに設定したレベル以下ならば出力しない
		$cmp = self::_compareLogLevel($level,$this->options['LOG_LEVEL']);
		if ( $cmp > 0 ){
			return;
		}

		// タグフィルタが設定されている場合、マッチしないログは無視
		$log_ta_filters = $this->options['LOG_TAG_FILTERS'];
		if ( is_array($log_ta_filters) && count($log_ta_filters) > 0 ){
			if ( !in_array( $msg->getTag(), $log_ta_filters ) ){
				return;
			}
		}

		foreach( $logger_names as $key )
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
/*
		$log_enabled    = Charcoal_Profile::getBoolean( 'LOG_ENABLED' );
		if ( !$log_enabled || $log_enabled->isFalse() )
		{
			$this->loggers = NULL;
			$this->buffer = NULL;
			return;
		}
*/
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
			list( $file, $line ) = Charcoal_System::caller(1);
			
			// get log level and logger names
			list( $level, $logger_names ) = self::_getLevelAndTargetList( $target );

			// create log message object
			$msg = new Charcoal_LogMessage( $level, $message, $tag, $file, $line, $logger_names );

			// get LOG_NO_BUFFER flag
			if ( $this->init && $this->options['LOG_NO_BUFFER'] === TRUE ){
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


<?php
/**
* base class for procedure
*
* PHP version 5
*
* @package    objects.procedures
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
abstract class Charcoal_AbstractProcedure extends Charcoal_CharcoalObject implements Charcoal_IProcedure
{
	const TAG = 'abstract_procedure';

	protected $task_manager;
	protected $forward_target;
	protected $modules;
	protected $events;
	protected $debug_mode;
	protected $log_enabled;
	protected $log_level;
	protected $log_loggers;

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		$this->task_manager        = us( $config->getString( 'task_manager', '' ) );
		$this->forward_target      = us( $config->getString( 'forward_target', '' ) );
		$this->modules             = uv( $config->getArray( 'modules', array() ) );
		$this->events              = uv( $config->getArray( 'events', array() ) );
		$this->debug_mode          = ub( $config->getBoolean( 'debug_mode', FALSE ) );
		$this->log_enabled         = ub( $config->getBoolean( 'log_enabled' ) );
		$this->log_level           = us( $config->getString( 'log_level' ) );
		$this->log_loggers         = uv( $config->getArray( 'log_loggers' ) );

		// eventsに記載しているイベントのモジュールも読み込む
		if ( is_array($this->events) ){
			foreach( $this->events as $event ){
				$pos = strpos( $event, "@" );
				if ( $pos !== FALSE ){
					$this->modules[] = substr( $event, $pos );
				}
			}
		}


		if ( $this->getSandbox()->isDebug() )
		{
			log_info( "system, debug, config",  "task_manager：" . $this->task_manager, self::TAG );
			log_info( "system, debug, config",  "forward_target：" . $this->forward_target, self::TAG );
			log_info( "system, debug, config",  "modules：" . $this->modules, self::TAG );
			log_info( "system, debug, config",  "events：" . $this->events, self::TAG );
			log_info( "system, debug, config",  "debug_mode" . $this->debug_mode, self::TAG );
			log_info( "system, debug, config",  "log_enabled" . $this->log_enabled, self::TAG );
		}
	}

	/*
	 *	returns TRUE if this procedure is debug mode
	 */
	public function isDebugMode()
	{
		return $this->debug_mode;
	}

	/*
	 *	returns TRUE if logger is enabled
	 */
	public function isLoggerEnabled()
	{
		return $this->log_enabled;
	}

	/*
	 *	returns log level
	 */
	public function getLogLevel()
	{
		return $this->log_level;
	}

	/*
	 *	returns loggers
	 */
	public function getLoggers()
	{
		return $this->log_loggers;
	}

	/*
	 * 転送先があるか
	 */
	public function hasForwardTarget()
	{
		return strlen($this->forward_target) > 0;
	}

	/*
	 * 転送先を取得
	 */
	public function getForwardTarget()
	{
		return new ProcedurePath( $this->forward_target );
	}

}


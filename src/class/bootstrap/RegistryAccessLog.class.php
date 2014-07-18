<?php
/**
* Maintains access log of registory
*
* PHP version 5
*
* @package    class.bootstrap
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_RegistryAccessLog extends Charcoal_Object
{
	private $sandbox;
	private $logs;

	/**
	 *  Constructor
	 */
	public function __construct( $sandbox )
	{
//		Charcoal_ParamTrait::checkSandbox( 1, $sandbox );

		$this->sandbox = $sandbox;
		$this->logs = NULL;

		parent::__construct();
	}

	/**
	 * add access log
	 * 
	 * @param string|Charcoal_String $log          log message
	 * @param integer|Chacoal_Integer $result      access result
	 */
	public function addLog( $log, $result )
	{
		Charcoal_ParamTrait::checkString( 1, $log );
		Charcoal_ParamTrait::checkInteger( 2, $result );

		if ( $this->sandbox->isDebug() ){
			$this->logs[] = array(
				'log' => us($log),
				'result' => $result,
				);
		}
	}

	/*
	 * get all access logs
	 */
	public function getAllLogs()
	{
		return $this->logs;
	}

	/*
	 * filter access logs
	 * 
	 * @param Charcoal_EnumRegistryAccessLogResult $result      access result mask
	 */
	public function filter( $result_mask )
	{
		Charcoal_ParamTrait::checkInteger( 1, $result_mask );

		$ret = array();
		foreach( $this->logs as $log ){
			$result = isset($log['result']) ? $log['result'] : 0;
			if ( ($result_mask & $result) != 0 ){
				$ret[] = $log;
			}
		}

		return $ret;
	}

}


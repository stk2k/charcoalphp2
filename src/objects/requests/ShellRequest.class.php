<?php
/**
* request class for shell
*
* PHP version 5
*
* @package    requests
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ShellRequest extends Charcoal_AbstractRequest
{
	private $_proc_path;
	private $_id;

	/*
	 *    コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();

		$argv = $_SERVER[ 'argv' ];
		$this->_values  = Charcoal_CommandLineUtil::parseParams( $argv );

		log_debug( "debug", "argv:" . print_r($this->_data,true) );

		$this->_id = strval(microtime(TRUE));
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		// プロシージャキー
		$proc_key  = $this->getSandbox()->getProfile()->getString( 'PROC_KEY', 'proc' );

		$obj_path = $this->get( $proc_key );

		$this->_proc_path = new Charcoal_ObjectPath( $obj_path );

	}

	/*
	 *    プロシージャパスを取得
	 */
	public function getProcedurePath()
	{
		return $this->_proc_path;
	}

	/*
	 * リクエストIDを取得
	 */
	public function getRequestID()
	{
		return $this->_id;
	}

	/*
	 *    URLを取得
	 */
	public function getURL()
	{
		return $this->_url;
	}

}


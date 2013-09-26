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
	/*
	 *    コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();

		$argv = $_SERVER[ 'argv' ];
		$this->values  = Charcoal_CommandLineUtil::parseParams( $argv );
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );
	}
}


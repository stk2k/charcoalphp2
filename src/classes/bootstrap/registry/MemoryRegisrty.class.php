<?php
/**
* cofig registry implemeted by memory
*
* PHP version 5
*
* @package    classes.bootstrap.registry
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_MemoryRegistry extends Charcoal_AbstractRegistry
{
	private $sandbox;

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
	 * get configuration data by key
	 * 
	 * @param string $key      registry data id
	 *
	 * @return array           configuration data
	 */
	public function get( $key )
	{
		// 'key' parameter is regarded as memory path(accessed by 'php://{memory-path}') in this class
		
	}

}


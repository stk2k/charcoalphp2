<?php
/**
* registry implemeted by file system
*
* PHP version 5
*
* @package    class.bootstrap.registry
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_FileSystemRegistry extends Charcoal_AbstractRegistry
{
	private $sandbox;

	/**
	 *  Constructor
	 */
	public function __construct( $sandbox )
	{
//		Charcoal_ParamTrait::validateSandbox( 1, $sandbox );

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
//		Charcoal_ParamTrait::validateString( 1, $key );

		// get config povier
		$provider = $this->sandbox->getConfigProvider();

		// 'key' parameter is regarded as file path in this class
		return $provider->loadConfig( $key, $this->sandbox->getRegistryAccessLog() );
	}

}


<?php
/**
* registry implemeted by file system
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_FileSystemRegistry extends Charcoal_Object implements Charcoal_IRegistry
{
	private $sandbox;

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
	 * get configuration data by key
	 * 
	 * @param string $key      registry data id
	 *
	 * @return array           configuration data
	 */
	public function get( $key )
	{
//		Charcoal_ParamTrait::checkString( 1, $key );

		// get config povier
		$provider = $this->sandbox->getConfigProvider();

		// 'key' parameter is regarded as file path in this class
		return $provider->loadConfig( $key );
	}

}


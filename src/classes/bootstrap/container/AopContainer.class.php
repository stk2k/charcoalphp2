<?php
/**
* codebase implemented by AOP Container
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_AopContainer extends Charcoal_AbstractContainer
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
	 * get class source
	 * 
	 * @param Charcoal_String $path         virtual path of the class
	 */
	public function getClassSource( $path )
	{

	}

}


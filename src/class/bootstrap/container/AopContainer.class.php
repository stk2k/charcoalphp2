<?php
/**
* codebase implemented by AOP Container
*
* PHP version 5
*
* @package    class.bootstrap.container
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_AopContainer extends Charcoal_AbstractContainer
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
	 * get class source
	 * 
	 * @param Charcoal_String $path         virtual path of the class
	 */
	public function getClassSource( $path )
	{

	}

}


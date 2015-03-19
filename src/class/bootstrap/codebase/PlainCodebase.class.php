<?php
/**
* codebase implemented by plain class
*
* PHP version 5
*
* @package    class.bootstrap.codebase
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_PlainCodebase extends Charcoal_AbstractCodebase
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
	 * get class source
	 * 
	 * @param Charcoal_String $path         virtual path of the class
	 */
	public function getClassSource( $path )
	{

	}

}


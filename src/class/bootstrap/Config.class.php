<?php
/**
* Container for configuration values
*
* PHP version 5
*
* @package    class.bootstrap
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_Config extends Charcoal_ConfigPropertySet
{
	private $env;

	/**
	 *  Constructor
	 */
	public function __construct( $env, array $data = NULL )
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_IEnvironment', $env );
		
		parent::__construct( $env, $data );
	}
}

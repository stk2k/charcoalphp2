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
    /**
     *  Constructor
     *
     * @param Charcoal_IEnvironment $env
     * @param array $values
     */
    public function __construct( $env, $values = NULL )
    {
//        Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_IEnvironment', $env );

        parent::__construct( $env, $values );
    }
}

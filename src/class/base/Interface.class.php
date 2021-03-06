<?php
/**
* Interface information class
*
* PHP version 5
*
* @package    class.base
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_Interface extends Charcoal_Object
{
    private $interface_name;

    /*
     *    Constructor
     */
    public function __construct( $interface_name )
    {
//        Charcoal_ParamTrait::validateString( 1, $interface_name );

        parent::__construct();

        $this->interface_name = $interface_name;
    }

    /*
     *  Get interface name
     *
     * @return string    interface name
     */
    public function getInterfaceName()
    {
        return $this->interface_name;
    }

    /*
     *  Check if an object implements this interface
     *
     */
    public function validateImplements( $object )
    {
        $interface_name = $this->interface_name;

        if ( !interface_exists($interface_name) ){
            _throw( new Charcoal_InterfaceNotFoundException( $interface_name ) );
        }

        if ( !($object instanceof $interface_name) ){
            // Invoke Exception
            _throw( new Charcoal_InterfaceImplementException( $object, $interface_name ) );
        }
    }

}


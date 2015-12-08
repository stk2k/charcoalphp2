<?php
/**
* Interface is not found exception 
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_InvalidShellArgumentException extends Charcoal_RuntimeException
{
    private $argument;
    private $option_name;

    public function __construct( $argument, $option_name, $prev = NULL )
    {
        Charcoal_ParamTrait::validateString( 1, $argument );
        Charcoal_ParamTrait::validateString( 2, $option_name );
        Charcoal_ParamTrait::validateException( 3, $prev, TRUE );

        $this->argument = $argument;
        $this->option_name = $option_name;

        parent::__construct( "Invalid argument for -$option_name: $argument", $prev );
    }

    public function getArgument()
    {
        return $this->argument;
    }

    public function getOptionName()
    {
        return $this->option_name;
    }

}



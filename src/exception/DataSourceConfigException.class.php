<?php
/**
* exception caused by configuration of component
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_DataSourceConfigException extends Charcoal_ConfigException
{
    public function __construct( $entry, $message = NULL, $prev = NULL )
    {
        Charcoal_ParamTrait::validateString( 1, $data_source_type );
        Charcoal_ParamTrait::validateString( 2, $entry );
        Charcoal_ParamTrait::validateString( 3, $message );
        Charcoal_ParamTrait::validateException( 4, $prev, TRUE );

        parent::__construct( "data dource($data_source_type) config maybe wrong: [entry]$entry [message]$message", $prev );
    }
}


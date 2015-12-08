<?php
/**
* exception caused by failure in finding procudure
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ProcedureNotFoundException extends Charcoal_ConfigException
{
    public function __construct( $proc_path, $prev = NULL )
    {
        parent::__construct( "Procedure not found: [$proc_path]", $prev );
    }
}



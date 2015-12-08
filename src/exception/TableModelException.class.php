<?php
/**
* テーブルモデル例外
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_TableModelException extends Charcoal_RuntimeException
{
    public function __construct( Charcoal_ITableModel $model, Charcoal_String $message, Exception $previous = NULL )
    {
        $msg  = " [table model]" . get_class($model);
        if ( $message ){
            $msg .= " [message]$message";
        }

        if ( $previous ) parent::__construct( s($msg), $previous ); else parent::__construct( s($msg) );
    }
}


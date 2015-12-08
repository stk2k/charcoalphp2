<?php
/**
* 例外ハンドラ
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IExceptionHandler
{
    /**
     * handle an exception
     *
     * @param Exception $e     exception to handle
     *
     * @return boolean        TRUE means the exception is handled, otherwise FALSE
     */
    public function handleException( $e );
}


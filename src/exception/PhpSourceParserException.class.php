<?php
/**
* exception caused by PHP source parser
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
* @license    http://www.opensource.org/licenses/mit-license.php MIT License
*/

class Charcoal_PhpSourceParserException extends Charcoal_RuntimeException 
{
    public function __construct( $err_code, $message, Exception $prev = NULL )
    {
        parent::__construct( "[code]$err_code [message]$message", $prev );
    }

}


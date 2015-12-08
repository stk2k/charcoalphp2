<?php
/**
* サムネイルコンポーネント例外
*
* PHP version 5
*
* @package    components.mail
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2012 CharcoalPHP Development Team
*/

class ThumbnailComponentException extends Charcoal_RuntimeException
{
    /***
     *    コンストラクタ
     **/
    public function __construct( Charcoal_String $message, Exception $previous = NULL )
    {
        if ( $previous )    parent::__construct( s($message), $previous );    else    parent::__construct( s($message) );
    }
}

return __FILE__;

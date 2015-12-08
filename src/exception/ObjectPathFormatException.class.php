<?php
/**
* exception caused by not suitable for object path
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ObjectPathFormatException extends Charcoal_RuntimeException
{
    private $obj_path;

    public function __construct( $obj_path, $message = NULL, $prev = NULL )
    {
        $this->obj_path = $obj_path;

        parent::__construct( "Bad object pathformat($message): $obj_path", $prev );
    }

    /**
     *  get object path
     */
    public function getObjectPath()
    {
        return $this->obj_path;
    }

}


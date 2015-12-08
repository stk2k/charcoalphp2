<?php
/**
* exception caused by HTTP status error 
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_HttpStatusException extends Charcoal_BusinessException
{
    private $status;

    public function __construct( $status, $prev = NULL )
    {
        $this->status = $status;

        parent::__construct( "HTTP status error: status=[$status]", $prev );
    }

    /**
     *  HTTP status code
     */
    function getStatusCode()
    {
        return $this->status;
    }
}


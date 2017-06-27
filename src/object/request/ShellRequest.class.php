<?php
/**
* request class for shell
*
* PHP version 5
*
* @package    objects.requests
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ShellRequest extends Charcoal_AbstractRequest
{
    /*
     *    コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();

        $argv = $_SERVER[ 'argv' ];
        $this->values  = Charcoal_CommandLineUtil::parseParams( $argv );
    }

}


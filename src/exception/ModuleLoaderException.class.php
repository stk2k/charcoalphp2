<?php
/**
* Exception when module loader fails
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ModuleLoaderException extends Charcoal_RuntimeException
{
    private $module_path;

    public function __construct( $module_path, $message = NULL, $prev = NULL )
    {
        $this->module_path = $module_path;

        parent::__construct( "$message: $module_path", $prev );
    }

    /**
     *  get module path
     */
    public function getModulePath()
    {
        return $this->module_path;
    }

}


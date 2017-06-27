<?php
/**
* interface of registry
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IRegistry
{
    /**
     * Get configuration data by key
     *
     * @param string[] $keys           key list
     * @param string $obj_path         object path
     * @param string $type_name        type name of the object
     *
     * @return mixed              configuration data
     */
    public function get( $keys, $obj_path, $type_name );

    /**
     * List objects in target directory
     *
     * @param string $path             path
     * @param string $type_name        type name of the object
     *
     * @return string[]            virtual paths of found objects
     */
    public function listObjects( $path, $type_name );
    
    /**
     * Dump loaded items
     *
     * @param bool|Charcoal_Boolean $return           If true, no echos and return array
     *
     * @return array|NULL
     */
    public function dumpLoadedItems( $return = FALSE );

}


<?php
/**
* interface of config provider
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

interface Charcoal_IConfigProvider extends Charcoal_ICharcoalObject
{
    /**
     * set options
     *
     * @param Charcoal_HashMap $options   option set to apply
     */
    public function setOptions( $options );

    /**
     *  get config last updated date
     *
     * @param  string|Charcoal_String $key                  config key
     *
     * @return int     last updated date(UNIX timestamp)
     */
    public function getConfigDate( $key );

    /**
     *  load config
     *
     * @param  string|Charcoal_String $key                  config key
     *
     * @return array   configure data
     */
    public function loadConfig( $key );

    /**
     * list objects in target directory
     *
     * @param string $path             path
     * @param string $type_name        type name of the object
     *
     * @return string[]            virtual paths of found objects
     */
    public function listObjects( $path, $type_name );

}


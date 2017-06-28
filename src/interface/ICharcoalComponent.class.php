<?php
/**
 * Interface of Charcoal framework component
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_ICharcoalComponent extends Charcoal_ICharcoalObject
{
    /**
     * Get configurations
     *
     * @return array   configuration data
     */
    public function getConfig();
    
    /**
     * Initialize instance
     *
     * @param array $config   configuration data
     */
    public function configure( $config );
    
    /**
     *   get component name
     */
    public function getComponentName();
    
    /**
     *   set component name
     *
     * @param string|Charcoal_String $component_name
     */
    public function setComponentName( $component_name );
}


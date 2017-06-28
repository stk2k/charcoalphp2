<?php
/**
* interface of container
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IContainer
{
    /**
     * Get component(generate if not exists)
     *
     * @param string|Charcoal_String $component_name      component path
     * @param array $args       constructor arguments
     * @param array $config           object configuration parameters
     *
     * @return Charcoal_ICharcoalComponent        component instance
     */
    public function getComponent( $component_name, $args = array(), $config = array() );

    /**
     * destruct instance
     */
    public function terminate();
}


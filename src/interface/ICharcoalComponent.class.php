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
     * Initialize instance
     *
     * @param array $config   configuration data
     */
    public function configure( $config );
}


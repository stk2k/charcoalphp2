<?php
/**
* Routing Rule interface
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IRoutingRule extends Charcoal_ICharcoalObject
{
    /*
     *  Get all keys
     *
     * @return array
     */
    public function getKeys();

    /*
     *  Get procedure path associated with a pattern
     *
     * @return string
     */
    public function getProcPath( Charcoal_String $pattern );

}


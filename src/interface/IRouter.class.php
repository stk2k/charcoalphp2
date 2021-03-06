<?php
/**
* Router interface
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IRouter extends Charcoal_ICharcoalObject
{

    /**
     * Lookup routing rules
     *
     * @return array returns combined array, FALSE if any pattern is matched.
     */
    public function route( Charcoal_IRequest $request, Charcoal_IRoutingRule $rule );
}


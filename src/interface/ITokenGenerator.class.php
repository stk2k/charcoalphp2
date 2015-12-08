<?php
/**
* token generator interface
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_ITokenGenerator extends Charcoal_ICharcoalObject
{

    /**
     * generate a token
     */
    public function generateToken();
}


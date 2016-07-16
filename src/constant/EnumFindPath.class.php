<?php
/**
* constant: finding paths
*
* PHP version 5
*
* @package    constant
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_EnumFindPath extends Charcoal_Enum
{
    const FIND_PATH_FRAMEWORK       = 0x00000001;
    const FIND_PATH_PROJECT         = 0x00000002;
    const FIND_PATH_APPLICATION     = 0x00000004;
    const FIND_PATH_ALL             = 0xffffffff;
}


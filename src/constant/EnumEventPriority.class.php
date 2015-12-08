<?php
/**
* Event priority constants
*
* PHP version 5
*
* @package    constant
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_EnumEventPriority extends Charcoal_Enum
{
    // relative priority
    const HIGHEST           =  900;    // highest priority
    const ABOVE_NORMAL      =  700;    // above normal priority
    const NORMAL            =  500;    // normal priority
    const BELOW_NORMAL      =  300;    // velow normal priority
    const LOWEST            =  100;    // lowest priority

    // named priority
    const CRITICAL          = 1000;    // must be processed all other events
    const SYSTEM            =  800;    // system level priority(such as security fault)
    const LAYOUT            =  700;    // priority for page control(such as page redirection)
    const VIEW_RENDERING    =  200;    // very low priority(such as view rendering)
}


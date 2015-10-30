<?php
/**
* Query Option
*
* PHP version 5
*
* @package    component.charcoal.db
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_EnumQueryOption extends Charcoal_Enum
{
	const NO_OPTIONS    = 0x00000000;	// No options

	const FOR_UPDATE    = 0x00000001;	// SELECT ... FOR UPDATE
	const DISTINCT      = 0x00000002;	// SELECT DISTINCT ...
}


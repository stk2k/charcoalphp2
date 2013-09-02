<?php
/**
* Query Option
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_EnumQueryOption extends Charcoal_Object
{
	const FOR_UPDATE    = 0x00000001;	// SELECT ... FOR UPDATE
	const DISTINCT      = 0x00000002;	// SELECT DISTINCT ...
}

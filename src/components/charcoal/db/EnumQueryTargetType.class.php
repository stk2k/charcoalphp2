<?php
/**
* Query Target Type
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_EnumQueryTargetType extends Charcoal_Object
{
	const TARGET_MODEL         = 1;	// model
	const TARGET_INNER_JOIN    = 2;	// INNER JOIN : +
	const TARGET_LEFT_JOIN     = 3;	// LEFT JOIN : (+
	const TARGET_RIGHT_JOIN    = 4;	// RIGHT JOIN : +)
	const TARGET_AS            = 5;	// as
	const TARGET_AS_NAME       = 6;	// as name
	const TARGET_ON            = 6;	// on
	const TARGET_ON_CONDITION  = 7;	// on condition
}
return __FILE__;

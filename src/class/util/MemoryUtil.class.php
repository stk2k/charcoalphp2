<?php
/**
* Utility class of memory calculations
*
* PHP version 5
*
* @package    class.util
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_MemoryUtil
{
	const DEFAULT_PRECISION    = 4;

	const BYTES_KB    = 1024.0;					// 1MB = 1024 Bytes
	const BYTES_MB    = 1048576.0;				// 1MB = 1024 * 1024 Bytes = 1048576 Bytes
	const BYTES_GB    = 1073741824.0;			// 1MB = 1024 * 1024 * 1024 Bytes = 1073741824 Bytes
	const BYTES_TB    = 1099511627776.0;		// 1MB = 1024 * 1024 * 1024 * 1024 Bytes = 1099511627776 Bytes
	
	/**
	 *	convert memory size
	 *
	 * @param integer input_size       memory size in bytes to be converted
	 * @param integer unit             memory unit to be converted
	 *
	 * @return integer     converted size
	 */
	public static function convertSize( $value, $unit, $precision = self::DEFAULT_PRECISION )
	{
		switch ( $unit ){
		case Charcoal_EnumMemoryUnit::UNIT_B:
			return (float)$value;
		case Charcoal_EnumMemoryUnit::UNIT_KB:
			return round( ((float)$value) / self::BYTES_KB, $precision );
		case Charcoal_EnumMemoryUnit::UNIT_MB:
			return round( ((float)$value) / self::BYTES_MB, $precision );
		case Charcoal_EnumMemoryUnit::UNIT_GB:
			return round( ((float)$value) / self::BYTES_GB, $precision );
		case Charcoal_EnumMemoryUnit::UNIT_TB:
			return round( ((float)$value) / self::BYTES_TB, $precision );
		}

		_throw( new Charcoal_UnsupportedMemoryUnitException($unit) );
	}

}



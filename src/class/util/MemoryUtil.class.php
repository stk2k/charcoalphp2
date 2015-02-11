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
	 * @param integer|Charcoal_Integer input_size       memory size in bytes to be converted
	 * @param integer|Charcoal_Integer unit             memory unit to be converted
	 *
	 * @return integer     converted size
	 */
	public static function convertSize( $value, $unit, $precision = self::DEFAULT_PRECISION )
	{
		$value = ui($value);
		$unit = ui($unit);

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

	/**
	 *	get byte size from string
	 *
	 * @param string|Charcoal_String size_string       string expression of byte size. ex) 2MB, 100KB, 3.5GB
	 *
	 * @return integer     size in bytes
	 */
	public static function getByteSizeFromString( $size_string )
	{
		$size_string = us($size_string);

		if ( ($pos=strpos($size_string,'TB')) > 0 ){
			// TB
			$number = substr($size_string,0,$pos);
			if ( is_numeric($number) ){
				return (integer)(self::BYTES_TB * $number);
			}
		}
		else if ( ($pos=strpos($size_string,'GB')) > 0 ){
			// GB
			$number = substr($size_string,0,$pos);
			if ( is_numeric($number) ){
				return (integer)(self::BYTES_GB * $number);
			}
		}
		else if ( ($pos=strpos($size_string,'MB')) > 0 ){
			// MB
			$number = substr($size_string,0,$pos);
			if ( is_numeric($number) ){
				return (integer)(self::BYTES_MB * $number);
			}
		}
		else if ( ($pos=strpos($size_string,'KB')) > 0 ){
			// KB
			$number = substr($size_string,0,$pos);
			if ( is_numeric($number) ){
				return (integer)(self::BYTES_KB * $number);
			}
		}
		else if ( ($pos=strpos($size_string,'B')) > 0 ){
			// B
			$number = substr($size_string,0,$pos);
			if ( is_numeric($number) ){
				return (integer)$number;
			}
		}
		else if ( is_numeric($size_string) ){
			return (integer)$size_string;
		}

		_throw( new Charcoal_InvalidArgumentException($size_string) );
	}

}



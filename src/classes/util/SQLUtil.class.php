<?php
/**
* SQLを扱うクラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_SQLUtil
{
	/**
	 *	プレースホルダーのリストを作成
	 *
	 */
	public static function makePlaceHolderList( Charcoal_Vector $values )
	{
		$values = uv($values);

		$str = "";
		foreach( $values as $value ){
			if ( strlen($str) > 0 ){
				$str .= ",";
			}
			$str .= "?";
		}

		return $str;
	}

}

return __FILE__;

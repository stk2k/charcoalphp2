<?php
/**
* SQLを扱うクラス
*
* PHP version 5
*
* @package    class.util
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_SQLUtil
{
    /**
     *    プレースホルダーのリストを作成
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



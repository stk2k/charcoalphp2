<?php
/**
* XMLデータを扱うクラス
*
* PHP version 5
*
* @package    class.util
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_XmlUtil
{
    /**
     *    配列データをXMLデータとして出力（文字列）
     *
     */
    public static function encode( $value, Charcoal_String $encoding_str = NULL )
    {
        $value = System::convertArrayRecursive($value);

        if ( $encoding_str ){
            $conv = Charcoal_EncodingConverter::fromString( $encoding_str );

            $from = $conv->getFromEncoding();
            $to = $conv->getToEncoding();

            $value = System::convertEncodingRecursive( $value, us($to), us($from) );
        }

        return json_encode( $value );
    }

}



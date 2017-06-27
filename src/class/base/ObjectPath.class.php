<?php
/**
* オブジェクト抽象パスを扱うクラス
*
* PHP version 5
*
* @package    class.core
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ObjectPath extends Charcoal_Object
{
    private $_object_path_string;
    private $_virtual_path;
    private $_real_path;
    private $_object_name;
    private $_dir_list;

    /*
     *    コンストラクタ
     */
    public function __construct( $object_path_string )
    {
//        Charcoal_ParamTrait::validateString( 1, $object_path_string );

        parent::__construct();

        $object_path_string = us($object_path_string);

        list($object_name,$virtual_path,$real_path,$dir_list) = self::_parsePath( $object_path_string );

        $this->_object_path_string  = $object_path_string;
        $this->_virtual_path        = $virtual_path;
        $this->_real_path           = $real_path;
        $this->_object_name         = $object_name;
        $this->_dir_list            = $dir_list;
    }

    /*
     * パスをパース
     */
    private static function _parsePath( $object_path_string )
    {
        // @あり
        $pos_at = strpos( $object_path_string, '@' );
        if ( FALSE === $pos_at ){
            // :なし
            $object_name = $object_path_string;
            $virtual_path = NULL;
        }
        else{
            // @あり
            $object_name = substr( $object_path_string, 0, $pos_at );
            $virtual_path = substr( $object_path_string, $pos_at+1 );
        }
/*
        // オブジェクト名のテスト
        if ( !preg_match("/^[\w\d_]+$/",$object_name) ){
            _throw( new Charcoal_ObjectPathFormatException( $object_name, "should be consist of alphabet + number" ) );
        }
*/

        // ディレクトリリスト
        $dir_list = NULL;
        if ( $virtual_path ){
            $dir_list = explode( ':', $virtual_path );

            // 最初は:でなければならない
            if ( strlen($dir_list[0]) > 0 ){
                _throw( new Charcoal_ObjectPathFormatException( $object_path_string, "must start with character ':'" ) );
            }

            // ディレクトリ名は1文字以上の英数字またはアンダースコアでなければならない
            for($i=1;$i<count($dir_list);$i++){
                $dir = $dir_list[$i];
                if ( !preg_match("/^[\w\d_]+$/",$dir) ){
                    _throw( new Charcoal_ObjectPathFormatException( $object_path_string, "should be consist of alphabet + number" ) );
                }
            }
        }

        // :を/に変換
        $real_path = ( $virtual_path ) ? str_replace( ":", "/", $virtual_path ) : NULL;

        return array( $object_name, $virtual_path, $real_path, $dir_list );
    }

    /*
     * 親ディレクトリを表すObjectPathを取得
     */
    public function getParentPath()
    {
        $pos = strrpos( $this->_virtual_path, ':' );

        if ( $pos === FALSE ){
            if ( strlen($this->_virtual_path) === 0 ){
                return NULL;
            }
            return new Charcoal_ObjectPath( '' );
        }

        $parent_path = substr($this->_virtual_path,0,$pos);

        return new Charcoal_ObjectPath( $parent_path );
    }

    /*
     * オブジェクトパス文字列を取得
     */
    public function getObjectPathString()
    {
        return $this->_object_path_string;
    }

    /**
     * get virtual path
     *
     * @return string        virtual path
     */
    public function getVirtualPath()
    {
        return $this->_virtual_path;
    }

    /**
     * get real path
     *
     * @return string        real path
     */
    public function getRealPath()
    {
        return $this->_real_path;
    }

    /*
     * オブジェクト名を取得
     */
    public function getObjectName()
    {
        return $this->_object_name;
    }

    /*
     * ディレクトリ配列を取得
     */
    public function getDirArray()
    {
        return new Charcoal_Vector( explode( ":" , $this->_virtual_path ) );
    }

    /*
     *  String expression of this object
     *
     * @return string
     */
    public function toString()
    {
        if ( strlen($this->_virtual_path) > 0 ){
            $str = $this->_object_name . '@' . $this->_virtual_path;
        }
        else{
            $str = $this->_object_name;
        }
        return us($str);
    }
}


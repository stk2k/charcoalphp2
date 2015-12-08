<?php
/**
* アノテーション付テーブルモデル
*
* PHP version 5
*
* @package    objects.table_models
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_AnnotaionTableModel extends Charcoal_CharcoalObject
{
    private $_class_vars;
    private $_primary_key;
    private $_foreign_keys;
    private $_db_fields;
    private $_annotaions;

    /*
     *    コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();

        $class_name  = get_class($this);
        $this->_class_vars  = get_class_vars($class_name);

        $this->_relations = array();

        $annotaions = array();

        // プライマリーキーと外部キーの確認
        foreach( $this->_class_vars as $field => $value )
        {
            // 最初がアンダースコアで始まるフィールドは対象外
            if ( strpos($field,"_") === 0 ){
                continue;
            }

            $annot_map = $this->parseAnnotation( $field, $value );

            // プライマリキーの確認
            if ( isset($annot_map['pk']) ){
                if ( $this->_primary_key ){
                    // 2回以上指定はできない
                    _throw( new Charcoal_AnnotaionException( $class_name, $field, "@pk", "@pk annotation must be one per model" ) );
                }
                $this->_primary_key = $field;
            }

            // 外部キーの確認
            if ( isset($annot_map['fk']) ){
                $a_value = $annot_map['fk'];
                $model_name = s($a_value);
                if ( !$model_name || $model_name->isEmpty() ){
                    _throw( new Charcoal_AnnotaionException( $class_name, $field, "@fk", "@fk annotation requires model name" ) );
                }
                $this->_foreign_keys[us($model_name)] = $field;
            }

            // DBフィールド
            if ( isset($annot_map['field']) ){
                $this->_db_fields[] = $field;
            }

            $annotaions[$field] = $annot_map;
        }

        $this->_annotaions = $annotaions;
    }

    /**
     *  Get annotated values by specified field name
     *
     * @param Charcoal_String|string $field
     *
     * @return Charcoal_AnnotationValue[]|NULL
     */
    public function getAnnotations( $field )
    {
        $field = us($field);

        if ( !$this->_annotaions ){
            return NULL;
        }
        if ( !isset($this->_annotaions[$field]) ){
            return NULL;
        }
        return $this->_annotaions[$field];
    }

    /**
     *  Get annotated value by specified field name
     *
     * @param Charcoal_String|string $field
     * @param Charcoal_String|string $annotation_key
     *
     * @return Charcoal_AnnotationValue|NULL
     */
    public function getAnnotationValue( $field, $annotation_key )
    {
        Charcoal_ParamTrait::validateString( 1, $field );
        Charcoal_ParamTrait::validateString( 2, $annotation_key );

        $field = us($field);
        $key   = us($annotation_key);

        if ( !$this->_annotaions ){
            return NULL;
        }
        if ( !isset($this->_annotaions[$field]) ){
            return NULL;
        }
        $annot_map = $this->_annotaions[$field];
        if ( !isset($annot_map[$key]) ){
            return NULL;
        }
        return $annot_map[$key];
    }

    /*
     *   プライマリキーフィールド名を取得
     */
    public function getPrimaryKey()
    {
        if ( $this->_primary_key ){
            return $this->_primary_key;
        }
        return NULL;
    }

    /**
     * test if field exists in this table model
     *
     * @param Charcoal_String|string  $field
     *
     * @return boolean      TRUE if the field exists, otherwise FALSE
     */
    public function fieldExists( $field )
    {
        Charcoal_ParamTrait::validateString( 1, $field );

        return isset($this->_class_vars[us($field)]);
    }

    /*
     *    get all field names
     */
    public function getFieldList()
    {
        return $this->_db_fields;
    }

    /*
     *   フィールドのデフォルト値を取得
     */
    public function getDefaultValue( $field )
    {
        Charcoal_ParamTrait::validateString( 1, $field );

        $field = s($field);

        $annot = $this->getAnnotationValue( $field, s('default') );

        if ( !$annot ){
            return NULL;
        }

        $value = $annot->getValue();
        if ( !$value ){
            return NULL;
        }
        $value = us( $value );

        return $value == 'NULL' ? NULL : $value;
    }

    /**
     *    parse annotations
     *
     * @param Charcoal_String|string $field
     * @param Charcoal_String|string $field_value
     *
     * @return array         annotation map
     */
    private function parseAnnotation( $field, $field_value )
    {
        $phrase_list = explode( ' ', $field_value );

        $annot_map = array();

        foreach( $phrase_list as $phrase ){
            if ( strpos($phrase,'@') === 0 ){
                $value = '';
                $params = array();
                // :までがアノテーション名
                $phrase = substr($phrase,1);
                $pos = strpos($phrase,':');
                if ( $pos === false ){
                    // 値とパラメータはなし
                    $name = $phrase;
                }
                else{
                    // 値とパラメータを分ける
                    $name = substr($phrase,0,$pos);
                    $value_and_params = substr($phrase,$pos+1);
                    $open = strpos($value_and_params,'[');
                    $close = strpos($value_and_params,']');
                    // パラメータがあるか
                    if ( $open === false && $close === false ){
                        // パラメータなし
                        $value = $value_and_params;
                        $params = array();
                    }
                    elseif ( $open > 0 && $close > 0 ){
                        // パラメータあり
                        $value = substr($value_and_params,0,$open);
                        $params = substr($value_and_params,$open+1,$close-$open-1);
                        $params = explode( ',', $params );
                    }
                    else{
                        // アノテーションフォーマット例外
                        _throw( new Charcoal_AnnotaionException( get_class($this), $field, $name, 'illegal format' ) );
                    }
                }
                // アノテーション追加
                $new_annot = new Charcoal_AnnotationValue( s($name), s($value), v($params) );

                $annot_map[$name] = $new_annot;
            }
        }

        return $annot_map;
    }

    /*
     *  String expression of this object
     *
     * @return string
     */
    public function toString()
    {
        return "[TableModel:" . get_class($this) . "]";
    }

}


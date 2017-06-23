<?php
/**
* デフォルトのテーブルモデル実装
*
* PHP version 5
*
* @package    objects.table_models
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
abstract class Charcoal_DefaultTableModel extends Charcoal_AnnotaionTableModel implements Charcoal_ITableModel
{
    private $_model_id;

    /*
     *    コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set model id
     *
     * @param string $model_id
     */
    public function setModelID( $model_id )
    {
//        Charcoal_ParamTrait::validateString( 1, $model_id );

        $this->_model_id = $model_id;
    }

    /**
     * モデルIDを取得
     */
    public function getModelID()
    {
        return $this->_model_id;
    }

    /**
     *    Returns table name
     *
     * @return string
     */
    public function getTableName()
    {
        if ( !property_exists($this,'___table_name') ){
            _throw( new Charcoal_TableModelException( $this, s('___table_name property is not set') ) );
        }
        return $this->___table_name;
    }

    /**
     *   validate primary key value
     *
     *  @param Charcoal_DTO $dto         target record
     *
     * @return boolean         TRUE if primary key value id valid, otherwise FALSE
     */
    public function validatePrimaryKeyValue( $dto )
    {
        $pk = parent::getPrimaryKey();
        
        if ( !property_exists($dto,$pk) ){
            return false;
        }

        $value = $dto->$pk;

        return $value !== NULL && $value !== 0;
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


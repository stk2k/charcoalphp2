<?php
/**
* デフォルトのテーブルモデル実装
*
* PHP version 5
*
* @package    table_models
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
abstract class Charcoal_DefaultTableModel extends Charcoal_AnnotaionTableModel implements Charcoal_ITableModel
{
	private $_model_id;

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * モデルIDを設定
	 */
	public function setModelID( Charcoal_String $model_id )
	{
		$this->_model_id = $model_id;
	}

	/**
	 * モデルIDを取得
	 */
	public function getModelID()
	{
		return $this->_model_id;
	}

	/*
	 *	テーブル名を取得
	 */
	public function getTableName()
	{
		if ( property_exists($this,'___table_name') ){
			return $this->___table_name;
		}
		_throw( new Charcoal_TableModelException( $this, s('___table_name property is not set') ) );
	}

	/*
	 *  check if primary key field value is valid
	 */
	public function isPrimaryKeyValid( Charcoal_TableDTO $dto )
	{
		$pk = parent::getPrimaryKey();

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


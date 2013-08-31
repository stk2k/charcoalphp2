<?php
/**
* テーブルモデルをキャッシュするクラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_TableModelCache
{
	private $_cache;
	private $_type;

	/*
	 *    唯一のインスタンス取得
	 */
	public static function getInstance()
	{
		static $singleton_;
		if ( $singleton_ == null ){
			$singleton_ = new Charcoal_TableModelCache();
		}
		return $singleton_;
	}

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		$this->_type = s('table_model');
	}

	/**
	 *	テーブルモデルを取得
	 */
	public static function get( Charcoal_String $model_name )
	{
		$instance = self::getInstance();

		$model_name = us($model_name);

		if ( isset($instance->_cache[$model_name]) ){
			return $instance->_cache[$model_name];
		}

		// ないので作る
		$model = Charcoal_Factory::createObject( s($model_name), $instance->_type );

		$model->setModelID( s($model_name) );

		// 設定
		$instance->_cache[$model_name] = $model;

		return $model;
	}

}



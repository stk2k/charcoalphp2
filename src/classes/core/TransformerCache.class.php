<?php
/**
* トランスフォーマをキャッシュするクラス
*
* PHP version 5
*
* @package    classes.core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_TransformerCache
{
	private $_cache;
	private $_type_name;

	/*
	 *    唯一のインスタンス取得
	 */
	public static function getInstance()
	{
		static $singleton_;
		if ( $singleton_ == null ){
			$singleton_ = new TransformerCache();
		}
		return $singleton_;
	}

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		$this->_type_name = new Charcoal_String('transformer');
	}

	/**
	 *	トランスフォーマを取得
	 */
	public static function getTransformer( Charcoal_String $transformer_name )
	{
		$instance = self::getInstance();

		if ( isset($instance->_cache[us($transformer_name)]) ){
			return $instance->_cache[us($transformer_name)];
		}

		// ないので作る
		$transformer = $this->getSandbox()->createObject( $transformer_name, $instance->_type_name );

		// 設定
		$instance->_cache[us($transformer_name)] = $transformer;

		return $transformer;
	}

}



<?php
/**
* アノテーション付テーブルモデル
*
* PHP version 5
*
* @package    table_models
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_AnnotaionTableModel extends Charcoal_CharcoalObject
{
	private $_class_vars;
	private $_primary_key;
	private $_foreign_keys;
	private $_db_fields;
	private $_annotaions;

	/*
	 *	コンストラクタ
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
					_throw( new Charcoal_AnnotaionException( $field, "@pk", "@pk annotation must be one per model" ) );
				}
				$this->_primary_key = $field;
			}

			// 外部キーの確認
			if ( isset($annot_map['fk']) ){
				$a_value = $annot_map['fk'];
				$model_name = $a_value->getValue();
				if ( !$model_name || $model_name->isEmpty() ){
					_throw( new Charcoal_AnnotaionException( $field, "@fk", "@fk annotation requires model name" ) );
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
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config )
	{
	}

	/*
	 *   アノテーションを取得
	 */
	public function getAnnotations( Charcoal_String $field )
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

	/*
	 *   アノテーション値を取得
	 */
	public function getAnnotationValue( Charcoal_String $field, Charcoal_String $annotation_key )
	{
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
		_throw( new Charcoal_TableModelException( $this, s('no primary key') ) );
	}

	/**
	 * フィールドが存在するか
	 */
	public function fieldExists( Charcoal_String $field_name )
	{
		return isset($this->_class_vars[$field_name]);
	}

	/*
	 *	get all field names
	 */
	public function getFieldList()
	{
		return $this->_db_fields;
	}

	/*
	 *   フィールドのデフォルト値を取得
	 */
	public function getDefaultValue( Charcoal_String $field )
	{
		$annot = $this->getAnnotation( $field, s('default') );

		if ( !$annot ){
			return NULL;
		}

		$value = $annot->getValue();
		if ( !$value ){
			return NULL;
		}
		$value = us( $value );

		// 特殊初期化処理
		switch( $value ){
		case 'this_year':
		case 'date_Y':
			$value = date('Y');		break;
		case 'date_y':
			$value = date('y');		break;
		case 'date_m':
			$value = date('m');		break;
		case 'this_month':
		case 'date_n':
			$value = date('n');		break;
		case 'date_d':
			$value = date('d');		break;
		case 'this_day':
		case 'date_j':
			$value = date('j');		break;
		case 'today':
			{
				$format = $this->getAnnotationParameter( $field, s('default'), i(0), s('Y-m-d') );
				$value = date(us($format));
			}
			break;
		}

		return $value == 'NULL' ? NULL : $value;
	}

	/*
	 *	アノテーションをパース
	 */
	private function parseAnnotation( $field, $field_value )
	{
		$phrase_list = explode( ' ', $field_value );

		$annot_map = array();

		foreach( $phrase_list as $phrase ){
			if ( strpos($phrase,'@') === 0 ){
				// :までがアノテーション名
				$phrase = substr($phrase,1);
				$pos = strpos($phrase,':');
				if ( $pos === false ){
					// 値とパラメータはなし
					$name = $phrase;
					$value = '';
					$params = array();
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
					else if ( $open > 0 && $close > 0 ){
						// パラメータあり
						$value = substr($value_and_params,0,$open);
						$params = substr($value_and_params,$open+1,$close-$open-1);
						$params = explode( ',', $params );
					}
					else{
						// アノテーションフォーマット例外
						_throw( new Charcoal_AnnotaionException( $field, $name, 'illegal format' ) );
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


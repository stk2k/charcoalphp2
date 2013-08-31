<?php
/**
*
* Property set container(Read-Only)
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Properties extends Charcoal_Primitive implements Charcoal_IProperties
{
	private $_map;

	/*
	 *	コンストラクタ
	 */
	public function __construct( $values = array() )
	{
		parent::__construct();

		$this->_map = new Charcoal_HashMap( $values );
	}

    /**
     *	unbox primitive value
     */
    public function unbox()
    {
        return $this->_map->unbox();
    }

	/*
	 *  Get all element values
	 *
	 * @return array
	 */
	public function getAll()
	{
		return $this->_map->getAll();
	}

	/*
	 *	キー一覧を取得
	 */
	public function getKeys()
	{
		return $this->_map->getKeys();
	}

	/*
	 *  キーがあるか
	 */
	public function keyExists( Charcoal_String $key )
	{
		$key = us($key);
		return $this->_map->keyExists( $key );
	}

	/*
	 *	要素値を取得
	 */
	public function get( Charcoal_String $key )
	{
		return $this->_map->get( us($key) );
	}

	/*
	 *  Get element value as string
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return Charcoal_String
	 */
	public function getString( Charcoal_String $key, Charcoal_String $default_value = NULL )
	{
		$value = $this->_map->get( us($key) );

		// 文字列として不正ならデフォルト値を返す
		if ( NULL === $value || !is_string($value) ){
			return $default_value;
		}

		return s($value);
	}

	/*
	 *  Get element value as array
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return Charcoal_Vector
	 */
	public function getArray( Charcoal_String $key, Charcoal_Vector $default_value = NULL )
	{
		$value = $this->_map->get( us($key) );

		// 配列値として不正ならデフォルト値を返す
		if ( NULL === $value || !is_array($value) ){
			return $default_value;
		}

		// 配列を返却
		return  v($value);
	}

	/*
	 *  Get element value as boolean
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return Charcoal_Boolean
	 */
	public function getBoolean( Charcoal_String $key, Charcoal_Boolean $default_value = NULL )
	{
		$value = $this->_map->get( us($key) );

		if ( is_string($value) ){
			$value = strtolower($value);
			switch($value){
			case 'true':
			case 'on':
			case 'yes':
				$value = TRUE;
				break;
			default:
				$value = FALSE;
				break;
			}
		}

		// ブール値として不正ならデフォルト値を返す
		if ( NULL === $value || !is_bool($value) ){
			return $default_value;
		}

		// ブール型にして返却
		return b($value);
	}

	/*
	 *  Get element value as integer
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return Charcoal_Integer
	 */
	public function getInteger( Charcoal_String $key, Charcoal_Integer $default_value = NULL )
	{
		$value = $this->_map->get( us($key) );

		// 整数値として不正ならデフォルト値を返す
		if ( NULL === $value || !is_numeric($value) ){
			return $default_value;
		}

		// 整数型にして返却
		return i($value);
	}

	/*
	 *  Get element value as float
	 *
	 * @param Charcoal_String $key   Key string to get
	 *
	 * @return Charcoal_Float
	 */
	public function getFloat( Charcoal_String $key, Charcoal_Float $default_value = NULL )
	{
		$value = $this->_map->get( us($key) );

		// 浮動小数点数として不正ならデフォルト値を返す
		if ( NULL === $value || !is_numeric($value) ){
			return $default_value;
		}

		// 浮動小数点数型にして返却
		return f($value);
	}

	/*
	 *	配列化
	 */
	public function toArray()
	{
		return $this->_map->toArray();
	}


}


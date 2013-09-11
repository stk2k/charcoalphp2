<?php
/**
* 汎用スタッククラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Stack extends Charcoal_Object
{
	private $_data;

	/*
	 *	コンストラクタ
	 */
	public function __construct( Charcoal_Vector $data = NULL )
	{
		parent::__construct();

		$data = $data ? $data : new Charcoal_Vector();

		$this->_data = $data->reverse();
	}

	/*
	 *	先頭の要素を取得
	 */
	public function getHead()
	{
		$cnt = $this->_data->count();
		if ( $cnt > 0 ){
			return $this->_data->getHead();
		}
		_throw( new EmptyStackException( $this ) );
	}

	/*
	 *	最後の要素を取得
	 */
	public function getTail()
	{
		$cnt = $this->_data->count();
		if ( $cnt > 0 ){
			return $this->_data->getTail();
		}
		_throw( new EmptyStackException( $this ) );
	}

	/**
	 *	Check if the collection is empty
	 *	
	 *	@return bool        TRUE if this collection has no elements, FALSE otherwise
	 */
	public function isEmpty()
	{
		return $this->_data->isEmpty();
	}

	/*
	 *	要素数を取得
	 */
	public function count()
	{
		return $this->_data->count();
	}

	/*
	 *	全ての要素を削除
	 */
	public function clear()
	{
		$this->_data = new Charcoal_Vector();
	}

	/*
	 *	要素を追加
	 */
	public function push( $item )
	{
		Charcoal_ParamTrait::checkObject( 1, $item );

		return $this->_data->add( $item );
	}

	/*
	 *	要素を取得
	 */
	public function pop()
	{
		$tail = $this->_data->removeTail();
		if ( !$tail ){
			_throw( new Charcoal_EmptyStackException( $this ) );
		}
		return $tail;
	}

	/*
	 *	配列化
	 */
	public function toArray()
	{
		return $this->_data->toArray();
	}

}


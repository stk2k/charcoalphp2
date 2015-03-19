<?php
/**
* 汎用スタッククラス
*
* PHP version 5
*
* @package    class.core
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_Stack extends Charcoal_Collection
{
	/*
	 *	先頭の要素を取得
	 */
	public function getHead()
	{
		$cnt = count($this->values);
		if ( $cnt === 0 ){
			_throw( new EmptyStackException( $this ) );
		}

		return $this->values[0];
	}

	/*
	 *	最後の要素を取得
	 */
	public function getTail()
	{
		$cnt = count($this->values);
		if ( $cnt === 0 ){
			_throw( new EmptyStackException( $this ) );
		}
		$i = $cnt - 1;
		return isset($this->values[$i]) ? $this->values[$i] : NULL;
	}

	/**
	 *	Check if the collection is empty
	 *	
	 *	@return bool        TRUE if this collection has no elements, FALSE otherwise
	 */
	public function isEmpty()
	{
		return empty( $this->values );
	}

	/*
	 *	要素を追加
	 */
	public function push( $item )
	{
		$this->values[] = $item;
	}

	/*
	 *	要素を取得
	 */
	public function pop()
	{
		$tail = array_pop( $this->values );
		if ( !$tail ){
			_throw( new Charcoal_StackEmptyException( $this ) );
		}

		return $tail;
	}

	/**
	 * convert to array
	 * 
	 * @return array
	 */
	public function toArray()
	{
		if ( is_array($this->values) ){
			return $this->values;
		}
		return array_diff( $this->values, array() );
	}

}


<?php
/**
* キュークラス
*
* PHP version 5
*
* @package    classes.core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Queue extends Charcoal_Vector
{
	/*
	 * キューからエントリを取り出す
	 *
	 */
	public function dequeue()
	{
		return $this->shift();
	}

	/*
	 * イベントをキューに追加する
	 *
	 */
	public function enqueue( Charcoal_Object $item )
	{
		$this->add( $item );
	}
}


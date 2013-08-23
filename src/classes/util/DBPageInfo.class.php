<?php
/**
* DBページ情報
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_DBPageInfo extends Charcoal_Object
{
	private $_page_size;
	private $_page;
	private $_total;
	private $_page_max;

	/*
	 *	コンストラクタ
	 */
	public function __construct( Charcoal_Integer $page_size, Charcoal_Integer $page, Charcoal_Integer $total )
	{
		$this->_page_size      = ui($page_size);
		$this->_page           = ui($page);
		$this->_total          = ui($total);

		$this->updatePageMax();
	}

	/*
	 *	ページサイズを取得
	 */
	public function getPageSize()
	{
		return $this->_page_size;
	}

	/**
	 *	set page size
	 */
	public function setPageSize( Charcoal_Integer $page_size )
	{
		$this->_page_size = ui($page_size);
	}

	/*
	 *	ページを取得
	 */
	public function getPage()
	{
		return $this->_page;
	}

	/*
	 *	総件数を取得
	 */
	public function getTotal()
	{
		return $this->_total;
	}

	/*
	 *	総ページ数を取得
	 */
	public function getPageMax()
	{
		return $this->_page_max;
	}

	/*
	 *	総ページ数を更新
	 */
	public function updatePageMax()
	{
		// ページ最大数
		$this->_page_max = ($this->_total == 0) ? 1 : intval(($this->_total-1) / $this->_page_size) + 1;
	}


	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return "[DBPageInfo page_size=" . $this->_page_size . " page=" . $this->_page . 
				" page_max=" . $this->_page_max . " total=" . $this->_total . "]";
	}
}
return __FILE__;

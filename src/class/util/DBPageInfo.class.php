<?php
/**
* DBページ情報
*
* PHP version 5
*
* @package    class.util
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_DBPageInfo extends Charcoal_Object
{
	private $_page_size;
	private $_page;
	private $_total;
	private $_page_max;

	/**
	 *	Constructor
	 *	
	 *	@param int $page_size
	 *	@param int $page
	 *	@param int $total
	 */
	public function __construct( $page_size, $page, $total )
	{
		$this->_page_size      = ui($page_size);
		$this->_page           = ui($page);
		$this->_total          = ui($total);

		$this->updatePageMax();
	}

	/**
	 *	Get page size
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


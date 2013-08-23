<?php
/**
* SQL条件クラス(ページング機能）
*
* PHP version 5
*
* @package    components.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_PagedSQLCriteria extends Charcoal_SQLCriteria
{
	private $_page_info;

	/*
	 *    コンストラクタ
	 */
	public function __construct( Charcoal_DBPageInfo $page_info, Charcoal_String $where = NULL, Charcoal_Vector $params = NULL, Charcoal_String $order_by = NULL, Charcoal_String $group_by = NULL )
	{
		$this->_page_info = $page_info;

		$page      = $page_info->getPage();
		$page_size = $page_info->getPageSize();

		$page      = ui($page);
		$page_size = ui($page_size);

		$limit = $page_size;
		$offset = ($page - 1) * $page_size;

		parent::__construct( s($where), v($params), s($order_by), i($limit), i($offset), s($group_by) );
	}

}
return __FILE__;

<?php
/**
* SQL criteria class for pagination
*
* PHP version 5
*
* PHP version 5
*
* @package    component.charcoal.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_PagedSQLCriteria extends Charcoal_SQLCriteria
{
	/**
	 * Constructor
	 */
	public function __construct( $page_info, $where = NULL, $params = NULL, $order_by = NULL, $group_by = NULL )
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_DBPageInfo', $page_info );
		Charcoal_ParamTrait::checkString( 2, $where, TRUE );
		Charcoal_ParamTrait::checkVector( 2, $params, TRUE );
		Charcoal_ParamTrait::checkString( 3, $order_by, TRUE );
		Charcoal_ParamTrait::checkString( 4, $group_by, TRUE );

		$this->page_info = $page_info;

		$page      = $page_info->getPage();
		$page_size = $page_info->getPageSize();

		$page      = ui($page);
		$page_size = ui($page_size);

		$limit = $page_size;
		$offset = ($page - 1) * $page_size;

		parent::__construct( $where, $params, $order_by, $limit, $offset, $group_by );
	}
}


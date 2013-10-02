<?php
/**
* タスク
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class QueryContextTestTask extends Charcoal_TestTask
{
	private $default_ds;
	private $another_ds;

	/**
	 * check if action will be processed
	 */
	public function isValidAction( $action )
	{
		switch( $action ){
		case "from":
		case "from_with_alias":
		case "join":
		case "join_with_alias":
		case "join_with_type_inner":
		case "join_with_type_left":
		case "join_with_type_right":
		case "inner_join":
		case "inner_join_with_alias":
		case "left_join":
		case "left_join_with_alias":
		case "right_join":
		case "rightt_join_with_alias":
		case "where":
		case "where_with_params":
		case "like":
		case "between":
		case "not_between":
		case "is":
		case "is_not":
		case "is_null":
		case "is_not_null":
		case "in":
		case "order_by":
		case "limit":
		case "offset":
		case "group_by":
		case "bind":
		case "count":
		case "max":
		case "min":
		case "sum":
		case "avg":
		case "select":
		case "selet_all":
		case "selet_all_for_update":
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * セットアップ
	 */
	public function setUp( $action, $context )
	{
		switch( $action ){
		case "from":
		case "from_with_alias":
		case "join":
		case "join_with_alias":
		case "join_with_type_inner":
		case "join_with_type_left":
		case "join_with_type_right":
		case "inner_join":
		case "inner_join_with_alias":
		case "left_join":
		case "left_join_with_alias":
		case "right_join":
		case "rightt_join_with_alias":
		case "where":
		case "where_with_params":
		case "like":
		case "between":
		case "not_between":
		case "is":
		case "is_not":
		case "is_null":
		case "is_not_null":
		case "in":
		case "order_by":
		case "limit":
		case "offset":
		case "group_by":
		case "bind":
		case "count":
		case "max":
		case "min":
		case "sum":
		case "avg":
		case "select":
		case "selet_all":
		case "selet_all_for_update":
			break;
		}
	}

	/**
	 * クリーンアップ
	 */
	public function cleanUp( $action, $context )
	{
	}

	/**
	 * テスト
	 */
	public function test( $action, $context )
	{
		$action = us($action);

		switch( $action ){
		case "from":
		case "from_with_alias":
		case "join":
		case "join_with_alias":
		case "join_with_type_inner":
		case "join_with_type_left":
		case "join_with_type_right":
		case "inner_join":
		case "inner_join_with_alias":
		case "left_join":
		case "left_join_with_alias":
		case "right_join":
		case "rightt_join_with_alias":
		case "where":
		case "where_with_params":
		case "like":
		case "between":
		case "not_between":
		case "is":
		case "is_not":
		case "is_null":
		case "is_not_null":
		case "in":
		case "order_by":
		case "limit":
		case "offset":
		case "group_by":
		case "bind":
		case "count":
		case "max":
		case "min":
		case "sum":
		case "avg":
		case "select":
		case "selet_all":
		case "selet_all_for_update":
			return TRUE;
		}

		return FALSE;
	}

}

return __FILE__;
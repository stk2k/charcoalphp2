<?php
/**
* SQLビルダを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

interface Charcoal_ISQLBuilder extends Charcoal_ICharcoalObject
{
	/*
	 *	SQL作成(SELECT)
	 */
	public  function buildSelectSQL( 
					Charcoal_ITableModel $model, 
					Charcoal_Integer $options, 
					Charcoal_SQLCriteria $criteria, 
					Charcoal_String $alias, 
					Charcoal_Vector $joins, 
					Charcoal_Vector $fields
				);

	/*
	 *	SQL作成(UPDATE)
	 */
	public  function buildUpdateSQL( Charcoal_ITableModel $model, Charcoal_DTO $dto, Charcoal_SQLCriteria $criteria, Charcoal_Properties $override = NULL );

	/*
	 *	SQL作成(INSERT)
	 */
	public  function buildInsertSQL( Charcoal_ITableModel $model, Charcoal_DTO $dto, Charcoal_Properties $override = NULL );

	/*
	 *	SQL作成(MIN/MAX/SUM/COUNT/AVG)
	 */
	public  function buildAggregateSQL( Charcoal_Integer $aggregate_func, Charcoal_ITableModel $model, Charcoal_SQLCriteria $criteria, Charcoal_String $alias, Charcoal_Vector $joins, Charcoal_String $fields );

	/*
	 *	SQL作成(DELETE)
	 */
	public  function buildDeleteSQL( Charcoal_ITableModel $model, Charcoal_SQLCriteria $criteria );

	/*
	 *	SQL作成(LAST_INSERT_ID)
	 */
	public  function buildLastIdSQL();

	/*
	 *	SQL作成(CREATE DATABASE)
	 */
	public  function buildCreateDatabaseSQL( Charcoal_String $db_name, Charcoal_String $charset = NULL );

	/*
	 *	SQL作成(CREATE TABLE)
	 */
	public  function buildCreateTableSQL( Charcoal_ITableModel $model );

	/*
	 *	ページ情報からLIMIT句で指定する値を生成
	 */
	public  function getLimit( Charcoal_DBPageInfo $page_info );

	/*
	 *	ページ情報からOFFSET句で指定する値を生成
	 */
	public  function getOffset( Charcoal_DBPageInfo $page_info );

}

return __FILE__;
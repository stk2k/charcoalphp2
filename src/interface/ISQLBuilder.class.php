<?php
/**
* SQLビルダを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

interface Charcoal_ISQLBuilder extends Charcoal_ICharcoalObject
{
	/**
	 *	Generate RDBMS-specific SQL for SELECT
	 *	
	 *	@param Charcoal_ITableModel $model        table model object related with th query
	 *	@param string $alias                      table model alias which is specified by $model
	 *	@param int $options                       options for SQL generation
	 *	@param Charcoal_SQLCriteria $criteria     criteria which should be used in WHERE clause
	 *	@param array $joins                       list of join(list of Charcoal_QueryJoin object)
	 *	@param array $fields                      list of fields which will be returned in query result
	 *	
	 *	@return string                            SQL
	 */
	public  function buildSelectSQL( $model, $alias, $options, $criteria, $joins, $fields = NULL );

	/**
	 *	Generate RDBMS-specific SQL for UPDATE
	 *	
	 *	@param Charcoal_ITableModel $model        table model object related with th query
	 *	@param string $alias                      table model alias which is specified by $model
	 *	@param Charcoal_DTO $data                 associative array or HashMap object to update
	 *	@param Charcoal_SQLCriteria $criteria     criteria which should be used in WHERE clause
	 *	@param array $override                    association field set which you want to override
	 *	
	 *	@return array                             the first element means SQL, the second element means parameter values
	 */
	public  function buildUpdateSQL( $model, $alias, $data, $criteria, $override = NULL );

	/**
	 *	Generate RDBMS-specific SQL for INSERT
	 *	
	 *	@param Charcoal_ITableModel $model        table model object related with th query
	 *	@param string $alias                      table model alias which is specified by $model
	 *	@param Charcoal_DTO $data                 associative array or HashMap object to insert
	 *	@param array $override                    association field set which you want to override
	 *	
	 *	@return array                             the first element means SQL, the second element means parameter values
	 */
	public  function buildInsertSQL( $model, $alias, $data, $override = NULL );

	/**
	 *	Generate RDBMS-specific SQL for bulk insert
	 *	
	 *	@param Charcoal_ITableModel $model        table model object related with th query
	 *	@param string $alias                      table model alias which is specified by $model
	 *	@param array $data_set                    array of array or HashMap of objects
	 *	@param array $override                    association field set which you want to override
	 *	
	 *	@return array                             the first element means SQL, the second element means parameter values
	 */
	public  function buildBulkInsertSQL( $model, $alias, $data_set, $override = NULL );

	/**
	 *	Generate RDBMS-specific SQL for MIN/MAX/SUM/COUNT/AVG
	 *	
	 *	@param Charcoal_ITableModel $model        table model object related with th query
	 *	@param string $alias                      table model alias which is specified by $model
	 *	@param int $aggregate_func                specify aggregate function which is defined in Charcoal_EnumSQLAggregateFunc
	 *	@param Charcoal_SQLCriteria $criteria     criteria which should be used in WHERE clause
	 *	@param array $joins                       list of join(list of Charcoal_QueryJoin object)
	 *	@param array $fields                      list of fields which will be returned in query result
	 *	
	 *	@return string                            SQL
	 */
	public  function buildAggregateSQL( $model, $alias, $aggregate_func, $criteria, $joins, $fields = NULL );

	/**
	 *	Generate RDBMS-specific SQL for DELETE
	 *	
	 *	@param Charcoal_ITableModel $model        table model object related with th query
	 *	@param string $alias                      table model alias which is specified by $model
	 *	@param Charcoal_SQLCriteria $criteria     criteria which should be used in WHERE clause
	 *	
	 *	@return string                            SQL
	 */
	public  function buildDeleteSQL( $model, $alias, $criteria );

	/**
	 *	Generate RDBMS-specific SQL for LAST_INSERT_ID
	 *
	 *	@return string                            SQL
	 */
	public  function buildLastIdSQL();

	/**
	 *	Generate RDBMS-specific SQL for CREATE TABLE
	 *	
	 *	@param Charcoal_ITableModel $model        table model object related with th query
	 *	@param boolean|Charcoal_Boolean $if_not_exists        If TRUE, output SQL includes "IF NOT EXISTS" wuth "CREATE TABLE"
	 *	
	 *	@return string                            SQL
	 */
	public  function buildCreateTableSQL( $model, $if_not_exists = false );

	/**
	 *	Generate RDBMS-specific SQL for DROP TABLE
	 *	
	 *	@param Charcoal_ITableModel $model        table model object related with th query
	 *	@param boolean|Charcoal_Boolean $if_exists        If TRUE, output SQL includes "IF EXISTS" wuth "DROP TABLE"
	 *	
	 *	@return string                            SQL
	 */
	public  function buildDropTableSQL( $model, $if_exists = false );

	/**
	 *	Generate RDBMS-specific SQL for TRUNCATE TABLE
	 *	
	 *	@param Charcoal_ITableModel $model        table model object related with th query
	 *	
	 *	@return string                            SQL
	 */
	public  function buildTruncateTableSQL( $model );

	/**
	 *	add RDBMS-specific comment after the specified SQL
	 *	
	 *	@param string|Charcoal_String             SQL
	 *	@param string|Charcoal_String $comment    comment text
	 *	
	 *	@return string                            SQL
	 */
	public  function appendComment( $sql, $comment );

	/**
	 *	add RDBMS-specific comment before the specified SQL
	 *	
	 *	@param string|Charcoal_String             SQL
	 *	@param string|Charcoal_String $comment    comment text
	 *	
	 *	@return string                            SQL
	 */
	public  function prependComment( $sql, $comment );
}


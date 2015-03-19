<?php

/**
* MYSQL用のSQLビルダ
*
* PHP version 5
*
* @package    objects.sql_builders
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_MySQL_SQLBuilder extends Charcoal_AbstractSQLBuilder
{
	/**
	 *	Generate RDBMS-specific SQL for CREATE TABLE
	 *	
	 *	@param Charcoal_ITableModel $model        table model object related with th query
	 *	@param boolean|Charcoal_Boolean $if_not_exists        If TRUE, output SQL includes "IF NOT EXISTS" wuth "CREATE TABLE"
	 *	
	 *	@return string                            SQL
	 */
	public  function buildCreateTableSQL( Charcoal_ITableModel $model, $if_not_exists = false )
	{
		Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_ITableModel', $model );
		Charcoal_ParamTrait::validateBoolean( 2, $if_not_exists );

		try{
			$field_list = $model->getFieldList();

			$SQL_pk_list    = array();
			$SQL_field_list = array();

			foreach( $field_list as $name )
			{
				$name = s($name);

				$pk       = $model->getAnnotation( $name, s('pk') );
				$type     = $model->getAnnotation( $name, s('type') );
				$notnull  = $model->getAnnotation( $name, s('notnull') );
				$charset  = $model->getAnnotation( $name, s('charset') );
				$serial   = $model->getAnnotation( $name, s('serial') );
				$default  = $model->getAnnotation( $name, s('default') );

				$type_name = $this->mapType( $type->getValue() );
				$length    = $type->getParameter();

				if ( $length && !$length->isEmpty() ){
					$field_expr = "`$name` $type_name($length)";
				}
				else{
					$field_expr = "`$name` $type_name";
				}

				if ( $pk ){
					$SQL_pk_list[] = "`$name`";
					if ( $notnull ){
						$field_expr .= ' NOT NULL';
					}
					if ( $serial ){
						$field_expr .= ' AUTO_INCREMENT';
					}
				}
				else{
					if ( $default ){
						$field_expr .= ' DEFAULT ' . $default->getValue();
					}
					if ( $charset ){
						$field_expr .= " CHARACTER SET $charset";
					}
					if ( $notnull ){
						$field_expr .= ' NOT NULL';
					}
				}

				$SQL_field_list[] = $field_expr;
			}

			$SQL_field_list = implode( ",\n", $SQL_field_list );
			$SQL_pk_list    = implode( ',', $SQL_pk_list );

			$table_name = $model->getTableName();
			$if_not_exists = ub($if_not_exists) ? 'IF NOT EXISTS' : '';
			$sql = "CREATE TABLE $if_not_exists `$table_name` (\n $SQL_field_list \n ,PRIMARY KEY( $SQL_pk_list ) )";

			return $sql;
		}
		catch ( Exception $e )
		{
			_throw( new Charcoal_SQLBuilderException( "MySQL_SQLBuilder#buildCreateTableSQL failed" ) );
		}
	}

	/**
	 *	Generate RDBMS-specific SQL for LAST_INSERT_ID
	 *	
	 *	@return string                            SQL
	 */
	public  function buildLastIdSQL()
	{
		return "select LAST_INSERT_ID()";
	}

}


<?php

/**
* MYSQL用のSQLビルダ
*
* PHP version 5
*
* @package    objects.sql_builders
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_MySQL_SQLBuilder extends Charcoal_AbstractSQLBuilder
{
	/**
	 *	Generate RDBMS-specific SQL for CREATE TABLE
	 *	
	 *	@param Charcoal_ITableModel $model        table model object related with th query
	 *	
	 *	@return string                            SQL
	 */
	public  function buildCreateTableSQL( Charcoal_ITableModel $model )
	{
		Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_ITableModel', $model );

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
			$sql = "CREATE TABLE $table_name (\n $SQL_field_list \n ,PRIMARY KEY( $SQL_pk_list ) )";

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


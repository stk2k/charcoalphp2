<?php

/**
* PostgreSQL用のSQLビルダ
*
* PHP version 5
*
* @package    sql_builders
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_PostgreSQL_SQLBuilder extends Charcoal_DefaultSQLBuilder
{
	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
	}

	/*
	 * SQLビルダ名を取得
	 */
	public function getSQLBuilderName()
	{
		return "MySQL SQL builder";
	}

	/*
	 *	SQL作成(CREATE DATABASE)
	 */
	public  function buildCreateDatabaseSQL( $db_name, $charset = NULL )
	{
		return "CREATE DATABASE $db_name WITH ENCODING='$charset'";
	}

	/*
	 *	SQL作成(CREATE TABLE)
	 */
	public  function buildCreateTableSQL( Charcoal_ITableModel $model )
	{
		try{
			$field_list = $model->getFieldList();

			$SQL_field_list = array();

			foreach( $field_list as $name )
			{
				$pk       = $model->getAnnotation( $name, 'pk' );
				$type     = $model->getAnnotation( $name, 'type' );
				$notnull  = $model->getAnnotation( $name, 'notnull' );
				$charset  = $model->getAnnotation( $name, 'charset' );
				$serial   = $model->getAnnotation( $name, 'serial' );
				$default  = $model->getAnnotation( $name, 'default' );

				$type_name = $this->mapType( $type->getValue() );
				$length    = $type->getParameter();

				if ( $length ){
					$field_expr = $name . ' ' . $type_name . '(' . $length . ')';
				}
				else{
					$field_expr = $name . ' ' . $type_name;
				}

				if ( $pk ){
					if ( $serial ){
						$field_expr = $name . ' serial primary key';
					}
					if ( $notnull ){
						$field_expr .= ' NOT NULL';
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

			$table_name = $model->getTableName();
			$sql = "CREATE TABLE $table_name (\n $SQL_field_list \n ) \n";

			return $sql;
		}
		catch ( Exception $e )
		{
			_throw( new Charcoal_SQLBuilderException( "PostgreSQL_SQLBuilder#buildCreateTableSQL failed" ) );
		}
	}

	/*
	 *	SQL作成(LAST_INSERT_ID)
	 */
	public  function buildLastIdSQL()
	{
		return "select LASTVAL()";
	}

}

return __FILE__;
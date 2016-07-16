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
    const TAG = 'mysql_sql_builder';

    /**
     *    Generate RDBMS-specific SQL for CREATE TABLE
     *
     *    @param Charcoal_ITableModel $model        table model object related with th query
     *    @param boolean|Charcoal_Boolean $if_not_exists        If TRUE, output SQL includes "IF NOT EXISTS" wuth "CREATE TABLE"
     *
     *    @return string                            SQL
     */
    public  function buildCreateTableSQL( $model, $if_not_exists = false )
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

                $pk       = $model->getAnnotationValue( $name, s('pk') );
                $type     = $model->getAnnotationValue( $name, s('type') );
                $notnull  = $model->getAnnotationValue( $name, s('notnull') );
                $charset  = $model->getAnnotationValue( $name, s('charset') );
                $serial   = $model->getAnnotationValue( $name, s('serial') );
                $default  = $model->getAnnotationValue( $name, s('default') );
                $comment  = $model->getAnnotationValue( $name, s('comment') );

                /** @var Charcoal_AnnotationValue $type */

                $type_name = $this->mapType( $type->getValue() );

                $field_expr = "`$name` $type_name";

                if ( $pk ){
                    $SQL_pk_list[] = "`$name`";
                    if ( $notnull ){
                        $field_expr .= ' NOT NULL';
                    }
                    if ( $serial ){
                        $field_expr .= ' AUTO_INCREMENT';
                    }
                    if ( $comment ){
                        $field_expr .= " COMMENT '" . $comment->getValue() . "'";
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
                    if ( $comment ){
                        $field_expr .= " COMMENT '" . $comment->getValue() . "'";
                    }
                }

                $SQL_field_list[] = $field_expr;
            }

            $SQL_field_list = implode( ",\n", $SQL_field_list );
            $SQL_pk_list    = implode( ',', $SQL_pk_list );

            $table_name = $model->getTableName();
            $if_not_exists = ub($if_not_exists) ? 'IF NOT EXISTS' : '';

            $sql = "CREATE TABLE $if_not_exists `$table_name` (\n $SQL_field_list \n ,PRIMARY KEY( $SQL_pk_list ) )";

            log_debug( "debug,sql,smart_gateway", "buildCreateTableSQL result: $sql", self::TAG );

            return $sql;
        }
        catch ( Exception $e )
        {
            _throw( new Charcoal_SQLBuilderException( "MySQL_SQLBuilder#buildCreateTableSQL failed" ) );
        }
        return '';
    }

    /**
     *    Generate RDBMS-specific SQL for LAST_INSERT_ID
     *
     *    @return string                            SQL
     */
    public  function buildLastIdSQL()
    {
        $sql = "select LAST_INSERT_ID()";
        log_debug( "debug,sql,smart_gateway", "buildLastIdSQL result: $sql", self::TAG );

        return $sql;
    }

    /**
     *    Generate RDBMS-specific SQL for TABLE EXISTS
     *
     *    @param string $database                   table schema
     *    @param Charcoal_ITableModel $model        table model object related with th query
     *
     *    @return string                            SQL
     */
    public  function buildExistsTableSQL( $database, $model )
    {
        Charcoal_ParamTrait::validateString( 1, $database );
        Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_ITableModel', $model );

        try{
            $table_name = $model->getTableName();
            $sql = "SELECT count(*) FROM information_schema.columns WHERE TABLE_NAME = '$table_name'";
            $sql .= " AND SCHEMA_NAME = '$database'";

            log_debug( "debug,sql,smart_gateway", "buildExistsTableSQL result: $sql", self::TAG );

            return $sql;
        }
        catch ( Exception $e )
        {
            _throw( new Charcoal_SQLBuilderException( "MySQL_SQLBuilder#buildCreateTableSQL failed" ) );
        }
        return '';
    }
}


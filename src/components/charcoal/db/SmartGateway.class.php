<?php

/**
* SmartGatewayクラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
require_once( 'EnumQueryOption' . CHARCOAL_CLASS_FILE_SUFFIX );

function qt( $value )
{
	if ( $value instanceof Charcoal_QueryTarget ){
		return $value;
	}
	return new Charcoal_QueryTarget( s($value) );
}

class Charcoal_SmartGateway extends Charcoal_CharcoalComponent implements Charcoal_IComponent
{
	private $data_source;
	private $sql_builder;

	private $data_source_name;

	private $last_sql;
	private $last_params;

	private $model_cache;

	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		// create data source
		$this->data_source_name = $this->getSandbox()->getProfile()->getString( 'DB_DATA_SOURCE' );

		log_debug( "smart_gateway, data_source", "data_source_name=" . $this->data_source_name );
	}

	/*
	 *	Close connection and destory component
	 */
	public function terminate()
	{
		if ( $this->data_source ){
			$this->data_source->disconnect();
		}
	}

	/*
	 * create data source
	 */
	public function initDataSource()
	{
		if ( !$this->data_source ){
			$this->data_source = $this->getSandbox()->createObject( $this->data_source_name, 'data_source' );

			log_info( "smart_gateway, data_source", "data source created: " . $this->data_source_name );
		}
	}

	/*
	 * create SQL builder
	 */
	public function initSQLBuilder()
	{
		if ( !$this->sql_builder ){
			// create SQL builder
			$sql_builder_name = $this->data_source->getBackend();

			$this->sql_builder = $this->getSandbox()->createObject( $sql_builder_name, 'sql_builder' );
			log_info( "smart_gateway, data_source", "SQL builder created: " . $sql_builder_name );
		}
	}

	/*
	 *	データソースを取得
	 */
	public function getDataSource()
	{
		return $this->data_source;
	}

	/*
	 *	データソースを選択
	 */
	public function setDataSource( Charcoal_IDataSource $source )
	{
		$this->data_source = $source;
	}

	/*
	 *	SQLビルダ
	 */
	public function getSQLBuilder()
	{
		return $this->sql_builder;
	}

	/*
	 *	get last executed SQL
	 */
	public function getLastSQL()
	{
		return $this->last_sql;
	}

	/*
	 *	get last executed parameters
	 */
	public function getLastParams()
	{
		return $this->last_params;
	}

	/**
	 *	get table model
	 *	
	 *	@param string $model_name       table model name
	 */
	public function getModel( $model_name )
	{
//		Charcoal_ParamTrait::checkString( 1, $model_name );

		$model_name = us($model_name);

		if ( isset($this->model_cache[$model_name]) ){
			return $this->model_cache[$model_name];
		}

		// create new instance
		$model = $this->getSandbox()->createObject( $model_name, 'table_model' );

		$model->setModelID( $model_name );

		// set in cache
		$this->model_cache[$model_name] = $model;

		return $model;
	}

	/*
	 *    自動コミット機能をON/OFF
	 */
	public function autoCommit( $on )
	{
		try{
			$this->initDataSource();

			if ( $onoff ){
				$this->data_source->autoCommit( $on );
			}
			else{
				$this->data_source->autoCommit();
			}
			log_debug( "debug,smart_gateway,sql", "done: autoCommit" );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBAutoCommitException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *    トランザクションを開始
	 */
	public function beginTrans()
	{
		try{
			$this->initDataSource();

			$this->data_source->beginTrans();
			log_debug( "debug,smart_gateway,sql", "done: beginTrans" );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBBeginTransactionException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *    コミットを発行
	 */
	public function commitTrans()
	{
		try{
			$this->initDataSource();

			$this->data_source->commitTrans();
			log_debug( "debug,smart_gateway,sql", "done: commitTrans" );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBCommitTransactionException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *    ロールバックを発行
	 */
	public function rollbackTrans()
	{
		try{
			$this->initDataSource();

			$this->data_source->rollbackTrans();
			log_debug( "debug,smart_gateway,sql", "done: rollbackTrans" );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBRollbackTransactionException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	条件を指定して更新（複数データ更新用）
	 */
	public  function updateAll( $model_name, $data, $criteria ) 
	{
		Charcoal_ParamTrait::checkString( 1, $model_name );
		Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_TableDTO', $data );
		Charcoal_ParamTrait::checkIsA( 3, 'Charcoal_SQLCriteria', $criteria );

		// データソースの準備
		$this->initDataSource();
		// SQLビルダの準備
		$this->initSQLBuilder();

		// テーブルモデル取得
		$model = $this->getModel( $model_name );

		try{
			list( $sql, $params ) = $this->sql_builder->buildUpdateSQL( $model, $data, $criteria );

//			log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//			log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

			// SQL実行
			$this->data_source->prepareExecute( s($sql), v($params) );

		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	特定データの項目を更新
	 */
	public  function updateField( $model_name, $data_id, $field, $value ) 
	{
		Charcoal_ParamTrait::checkString( 1, $model_name );
		Charcoal_ParamTrait::checkInteger( 2, $data_id );
		Charcoal_ParamTrait::checkString( 3, $field );
		Charcoal_ParamTrait::checkPrimitive( 4, $value );

		// データソースの準備
		$this->initDataSource();
		// SQLビルダの準備
		$this->initSQLBuilder();

		// テーブルモデル取得
		$model = $this->getModel( $model_name );

		try{

			// DTO
			$dto = $model->createDTO();

			$dto->$field = $value->unbox();

//			log_debug( "debug,smart_gateway,sql", "dto:" . print_r($dto,true) );

//			log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//			log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

			// 条件
			$pk = $model->getPrimaryKey();
			$where = "$pk = ?";
			$params = array( ui($data_id) );
			$criteria = new Charcoal_SQLCriteria( s($where), v($params) );

			// SQLとパラメータ作成
			list( $sql, $params ) = $this->sql_builder->buildUpdateSQL( $model, $dto, $criteria );

//			log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//			log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

			// SQL実行
			$this->data_source->prepareExecute( s($sql), v($params) );

		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	特定データの項目を現在時刻(NOW)で更新
	 */
	public  function updateFieldNow( $model_name, $data_id, $field ) 
	{
		Charcoal_ParamTrait::checkString( 1, $model_name );
		Charcoal_ParamTrait::checkInteger( 2, $data_id );
		Charcoal_ParamTrait::checkString( 3, $field );

		// データソースの準備
		$this->initDataSource();
		// SQLビルダの準備
		$this->initSQLBuilder();

		// テーブルモデル取得
		$model = $this->getModel( $model_name );

		try{
			$field = us($field);

			// アノテーションオーバーライド
			$override[$field]['update'] = new Charcoal_AnnotationValue( s('update'), s('function'), v(array('now')) );

//			log_debug( "debug,smart_gateway,sql", "override:" . print_r($override,true) );

			// 条件
			$pk = $model->getPrimaryKey();
			$where = "$pk = ?";
			$params = array( ui($data_id) );
			$criteria = new Charcoal_SQLCriteria( s($where), v($params) );

			// SQLとパラメータ作成
			list( $sql, $params ) = $this->sql_builder->buildUpdateSQL( $model, $model->createDTO(), $criteria, p($override) );

//			log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//			log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

			// SQL実行
			$this->data_source->prepareExecute( s($sql), v($params) );

		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}


	/*
	 *	特定データの複数の項目を更新
	 */
	public  function updateFields( $model_name, $data_id, $data ) 
	{
		Charcoal_ParamTrait::checkString( 1, $model_name );
		Charcoal_ParamTrait::checkInteger( 2, $data_id );
		Charcoal_ParamTrait::checkProperties( 3, $data );

		// データソースの準備
		$this->initDataSource();
		// SQLビルダの準備
		$this->initSQLBuilder();

		// テーブルモデル取得
		$model = $this->getModel( $model_name );

		try{
			// DTO
			$dto = $model->createDTO();

			$data = up($data);
			foreach( $data as $field => $value ){
				$dto->$field = $value;
			}

//			log_debug( "debug,smart_gateway,sql", "dto:" . print_r($dto,true) );

			// 条件
			$pk = $model->getPrimaryKey();
			$where = "$pk = ?";
			$params = array( ui($data_id) );
			$criteria = new Charcoal_SQLCriteria( s($where), v($params) );

			// SQLとパラメータ作成
			list( $sql, $params ) = $this->sql_builder->buildUpdateSQL( $model, $dto, $criteria );

//			log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//			log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

			// SQL実行
			$this->data_source->prepareExecute( s($sql), v($params) );

		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	挿入
	 */
	public  function insert( $model_name, $save_data )
	{
		Charcoal_ParamTrait::checkString( 1, $model_name );
		Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_TableDTO', $save_data );

		$dto  = clone $save_data;

		// データソースの準備
		$this->initDataSource();
		// SQLビルダの準備
		$this->initSQLBuilder();

		// テーブルモデル取得
		$model = $this->getModel( $model_name );

		try{
			// SQLを作成
			list( $sql, $params ) = $this->sql_builder->buildInsertSQL( $model, $dto );

//			log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//			log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

			// SQL実行
			$this->data_source->prepareExecute( s($sql), v($params) );

			// IDを返す
			$sql = $this->sql_builder->buildLastIdSQL();
			
			// 実行
			$result = $this->data_source->prepareExecute( s($sql) );

			// フェッチ
			$row = $this->data_source->fetchArray( $result );

			$new_id = $row[0];
			log_debug( "debug,smart_gateway,sql", "new_id:$new_id" );

			return $new_id;
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	保存
	 */
	public  function save( $model_name, $save_data )
	{
		Charcoal_ParamTrait::checkString( 1, $model_name );
		Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_TableDTO', $save_data );

		$dto  = clone $save_data;

		// データソースの準備
		$this->initDataSource();
		// SQLビルダの準備
		$this->initSQLBuilder();

		// テーブルモデル取得
		$model = $this->getModel( $model_name );

		try{
			// プライマリキー
			$pk      = $model->getPrimaryKey();

			// SQLを作成
			$is_new = FALSE;
			if ( $model->isPrimaryKeyValid($save_data) ){
				// プライマリキーの値が指定されていればUPDATE
				$data_id = $dto->$pk;

				$where = "$pk = ?";
				$params = array( ui($data_id) );
				$criteria = new Charcoal_SQLCriteria( s($where), v($params) );
				list( $sql, $params ) = $this->sql_builder->buildUpdateSQL( $model, $dto, $criteria );
			}
			else{
				// プライマリキーの値が指定されていなければINSERT
				list( $sql, $params ) = $this->sql_builder->buildInsertSQL( $model, $dto );

				$is_new = TRUE;
			}

//			log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//			log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

			// SQL実行
			$this->data_source->prepareExecute( s($sql), v($params) );

			// IDを返す
			if ( $is_new ){
				$sql = $this->sql_builder->buildLastIdSQL();

//				log_debug( "debug,smart_gateway,sql", "sql:$sql" );
					
				// 実行
				$result = $this->data_source->prepareExecute( s($sql) );

				// フェッチ
				$row = $this->data_source->fetchArray( $result );

				$new_id = $row[0];
			}
			else{
				$new_id = $dto->$pk;
			}

			log_debug( "debug,smart_gateway,sql", "new_id:$new_id" );

			return $new_id;
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	SQL実行(INSERT/DELETE/UPDATE)
	 */
	public  function execute( $sql, $params = NULL )
	{
		Charcoal_ParamTrait::checkString( 1, $sql );
		Charcoal_ParamTrait::checkVector( 2, $params, TRUE );

		// データソースの準備
		$this->initDataSource();
		// SQLビルダの準備
		$this->initSQLBuilder();

		try{
			// 実行
			$this->data_source->prepareExecute( $sql, $params );
		}
		catch ( Exception $e ) 
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	SQL実行(SELECT)
	 */
	public  function queryValue( $sql, $params = NULL )
	{
		Charcoal_ParamTrait::checkString( 1, $sql );
		Charcoal_ParamTrait::checkVector( 2, $params, TRUE );

		// データソースの準備
		$this->initDataSource();
		// SQLビルダの準備
		$this->initSQLBuilder();

		try{
			// DataSourceコンポーネントを取得
			$ds = $this->data_source;

			// 実行
			$result = $ds->prepareExecute( $sql, $params );

			// フェッチ
			while( $row = $ds->fetchAssoc( $result ) ){
				$value = array_shift($row);
				log_debug( "debug,smart_gateway,sql", "queryValue:$value" );
				return $value;
			}
			log_warning( "debug,smart_gateway,sql", "smart_gateway", "queryValue: no record" );

			return NULL;
		}
		catch ( Exception $e ) 
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	SQL実行(SELECT)
	 */
	public  function query( $sql, $params = NULL )
	{
		Charcoal_ParamTrait::checkString( 1, $sql );
		Charcoal_ParamTrait::checkVector( 2, $params, TRUE );

		// データソースの準備
		$this->initDataSource();
		// SQLビルダの準備
		$this->initSQLBuilder();

		try{
			// DataSourceコンポーネントを取得
			$ds = $this->data_source;

			// 実行
			$result = $ds->prepareExecute( $sql, $params );

			// フェッチ
			$a = array();
			while( $row = $ds->fetchAssoc( $result ) ){
				$a[] = $row;
			}

			return $a;
		}
		catch ( Exception $e ) 
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	SQL検索
	 */
	public  function find( $query_target, $options, $criteria, $fields = NULL ) 
	{
		try{
			Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
			Charcoal_ParamTrait::checkInteger( 2, $options );
			Charcoal_ParamTrait::checkIsA( 3, 'Charcoal_SQLCriteria', $criteria );
			Charcoal_ParamTrait::checkVector( 4, $fields, TRUE );

			$ret_val = array();

			// データソースの準備
			$this->initDataSource();
			// SQLビルダの準備
			$this->initSQLBuilder();

			// DataSourceを取得
			$ds = $this->data_source;

			$current_model_name  = $query_target->getModelName();
			$current_model_alias = $query_target->getAlias();
			$current_model_joins = $query_target->getJoins();
		
			// get current model
			$current_model = $this->getModel( s($current_model_name) );

			$current_table_name  = $current_model->getTableName();

			// make output fields
			if ( $fields === NULL ){
				// pickup fields from model
				$fields = $current_model->getFieldList();

				// add alias or table name as prefix
				$out_fields = NULL;
				foreach( $fields as $field ){
					$out_field = $field;
					if ( strlen($current_model_alias) > 0 ){
						$out_field = $current_model_alias . '.' . $out_field;
					}
					else if ( $current_model_joins && count($current_model_joins) > 0 ){
						$out_field = $current_table_name . '.' . $out_field;
					}
					$out_fields[] = $out_field;
				}

				// add join fields
				if ( $current_model_joins ){
					foreach( $current_model_joins as $join ){
						$join_model_name = $join->getModelName();
						$join_alias = $join->getAlias();

						$join_model = $this->getModel( s($join_model_name) );
						$join_fields = $join_model->getFieldList();
						foreach( $join_fields as $field ){
							$out_field = $field;
							if ( strlen($join_alias) > 0 ){
								$out_field = $join_alias . '.' . $out_field;
							}
							else{
								$out_field = $join_model_name . '.' . $out_field;
							}
							$out_fields[] = $out_field;
						}
					}
				}

				// make vector fields
				$fields = new Charcoal_Vector( $out_fields );
			}

			// SQLの作成
			$sql = $this->sql_builder->buildSelectSQL( $current_model, $options, $criteria, s($current_model_alias), v($current_model_joins), $fields );
//			log_debug( "debug,smart_gateway,sql", "SQL: $sql" );

			// パラメータ
			$params = $criteria->getParams();
//			log_debug( "debug,smart_gateway,sql", "params: $params" );

			// 実行
			$result = $ds->prepareExecute( s($sql), v($params) );

			$this->last_sql = $sql;
			$this->last_params = $params;

//			log_debug( "debug,smart_gateway,sql", "executed SQL: $sql" );

			// 実行結果件数取得
			$num_rows = $ds->numRows( $result );

//			log_debug( "debug,smart_gateway,sql", "num_rows: $num_rows" );

			// fetch by record
			while( $row = $ds->fetchAssoc( $result ) )
			{
//				log_debug( "debug,smart_gateway,sql", "row: " . print_r($row,true) );

				// create table DTO for a record
				$dto = new Charcoal_TableDTO( $row );

				$ret_val[] = $dto;
			}
		}
		catch ( Exception $e ) 
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}

		return $ret_val;
	}

	/*
	 *	最初の１件取得
	 */
	public  function findFirst( $query_target, $criteria, $fields = NULL ) 
	{
		try{
			Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
			Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
			Charcoal_ParamTrait::checkVector( 3, $fields, TRUE );

			// LIMIT=1に
			$criteria->setLimit( i(1) );

			// 検索実行
			if ( $fields )
				$result = $this->findAll( $query_target, $criteria, $fields );
			else
				$result = $this->findAll( $query_target, $criteria );

			return $result ? array_shift($result) : NULL;
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	最初の１件を更新用に取得
	 */
	public  function findFirstForUpdate( $query_target, $criteria, $fields = NULL ) 
	{
		try{
			Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
			Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
			Charcoal_ParamTrait::checkVector( 3, $fields, TRUE );

			// LIMIT=1に
			$criteria->setLimit( i(1) );

			// 検索実行
			$options = Charcoal_EnumQueryOption::FOR_UPDATE;
			if ( $fields )
				$result = $this->findAllForUpdate( $query_target, $criteria, $fields );
			else
				$result = $this->findAllForUpdate( $query_target, $criteria );

			return $result ? array_shift($result) : NULL;
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	全件取得
	 */
	public  function findAll( $query_target, $criteria, $fields = NULL ) 
	{
		try{
			Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
			Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
			Charcoal_ParamTrait::checkVector( 3, $fields, TRUE );

			// データソースの準備
			$this->initDataSource();
			// SQLビルダの準備
			$this->initSQLBuilder();

			// 検索実行
			if ( $fields )
				$result = $this->find( $query_target, i(0), $criteria, $fields );
			else
				$result = $this->find( $query_target, i(0), $criteria );

			return $result;
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	全件取得（更新用）
	 */
	public  function findAllForUpdate( $query_target, $criteria, $fields = NULL ) 
	{
		try{
			Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
			Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
			Charcoal_ParamTrait::checkVector( 3, $fields, TRUE );

			// データソースの準備
			$this->initDataSource();
			// SQLビルダの準備
			$this->initSQLBuilder();

			// 検索実行
			$options = Charcoal_EnumQueryOption::FOR_UPDATE;
			if ( $fields )
				$result = $this->find( $query_target, i($options), $criteria, $fields );
			else
				$result = $this->find( $query_target, i($options), $criteria );

			return $result;
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	全件取得（重複削除）
	 */
	public  function findDistinct( $query_target, $fields, $criteria, $fields = NULL ) 
	{
		try{
			Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
			Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
			Charcoal_ParamTrait::checkVector( 3, $fields, TRUE );

			// データソースの準備
			$this->initDataSource();
			// SQLビルダの準備
			$this->initSQLBuilder();

			// 検索実行
			$options = Charcoal_EnumQueryOption::DISTINCT;
			if ( $fields )
				$result = $this->find( $query_target, i($options), $criteria, $fields );
			else
				$result = $this->find( $query_target, i($options), $criteria );

			return $result;
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	１つのフィールドのみで検索
	 */
	public  function findAllBy( $query_target, $field, $value, $fields = NULL )
	{
		try{
			Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_QueryTarget', $query_target );
			Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
			Charcoal_ParamTrait::checkVector( 3, $fields, TRUE );

			// データソースの準備
			$this->initDataSource();
			// SQLビルダの準備
			$this->initSQLBuilder();

			$field = us( $field );

			$criteria = new Charcoal_SQLCriteria();

			$criteria->setWhere( $field . ' = ?' );
			$criteria->setParams( array( $value ) );

			// 検索実行
			if ( $fields )
				$result = $this->findAll( $query_target, $criteria, $fields );
			else
				$result = $this->findAll( $query_target, $criteria );

			return $result;
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	IDから検索(１つだけ指定した場合)
	 */
	public  function findByID( $model_name, $id ) 
	{
		try{
			Charcoal_ParamTrait::checkString( 1, $model_name );
			Charcoal_ParamTrait::checkInteger( 2, $id );

			// データソースの準備
			$this->initDataSource();
			// SQLビルダの準備
			$this->initSQLBuilder();

			$model = $this->getModel( s($model_name) );

			// プライマリキーフィールド名を取得
			$pk = $model->getPrimaryKey();

			// １つだけ指定した場合
			$where_clause = s( $pk . ' = ?');
			$params = v(array( Charcoal_System::toString($id) ));

			$criteria = new Charcoal_SQLCriteria( $where_clause, $params );

			$result = $this->findAll( qt($model_name), $criteria );

			return $result ? array_shift( $result ) : NULL;

		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	削除（１つだけ指定した場合）
	 */
	public  function destroyById( $model_name, $id ) 
	{
		try{
			Charcoal_ParamTrait::checkString( 1, $model_name );
			Charcoal_ParamTrait::checkInteger( 2, $id );

			// データソースの準備
			$this->initDataSource();
			// SQLビルダの準備
			$this->initSQLBuilder();

			$id = us( $id );

			// テーブルモデル取得
			$model = $this->getModel( $model_name );

			// キーフィールド名
			$pk = us($model->getPrimaryKey());

			// SQLパラメータ
			$params = array( ui($id) );

			// 条件
			$criteria = new Charcoal_SQLCriteria();
			$criteria->setWhere( s($pk . ' = ?') );
			$criteria->setParams( v($params) );

			// SQLビルダを使いSQLを生成
			$sql = $this->sql_builder->buildDeleteSQL( $model, $criteria );

///			log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//			log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

			$this->execute( s($sql), v($params) );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	削除（複数を指定した場合）
	 */
	public  function destroyAllById( $model_name, $id_array ) 
	{
		try{
			Charcoal_ParamTrait::checkString( 1, $model_name );
			Charcoal_ParamTrait::checkVector( 2, $id_array );

			// データソースの準備
			$this->initDataSource();
			// SQLビルダの準備
			$this->initSQLBuilder();

			// テーブルモデル取得
			$model = $this->getModel( $model_name );

			// テーブル名
			$table = $model->getTableName();

			// 複数個指定した場合
			$where = array();
			$params = array();
			
			foreach( $id_array as $id ){
				$where[] = "?";
				$params[] = $id;
			}

			$sql = "delete from $table where id in (" . implode(",",$where) . ")";

//			log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//			log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

			$this->execute( $sql, $params );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	１つのフィールドに合致するレコードを全削除
	 */
	public  function destroyBy( $model_name, $field, $value )
	{
		try{
			Charcoal_ParamTrait::checkString( 1, $model_name );
			Charcoal_ParamTrait::checkString( 2, $field );
			Charcoal_ParamTrait::checkString( 3, $value );

			// データソースの準備
			$this->initDataSource();
			// SQLビルダの準備
			$this->initSQLBuilder();

			$where = us($field) . ' = ?';
			$params = array( us($value) );

			$criteria = new Charcoal_SQLCriteria( s($where), v($params) );

			$this->destroyAll( $model_name, $criteria );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}


	/*
	 *	条件に合致するレコードを全削除
	 */
	public  function destroyAll( $model_name, $criteria ) 
	{
		try{
			Charcoal_ParamTrait::checkString( 1, $model_name );
			Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );

			// データソースの準備
			$this->initDataSource();
			// SQLビルダの準備
			$this->initSQLBuilder();

			// テーブルモデル取得
			$model = $this->getModel(  $model_name );

			// キーフィールド名
//			$pk = $model->getPrimaryKey();

			// SQLビルダを使いSQLを生成
			$sql = $this->sql_builder->buildDeleteSQL( $model, $criteria );

			// パラメータ
			$params = $criteria ? $criteria->getParams() : NULL;

//			log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//			log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

			// SQLを実行
			$this->execute( s($sql), v($params) );
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	Count entries in sigle table
	 */
	private  function _executeAggregateSQL( $aggregate_func, $query_target, $criteria, $fields ) 
	{
		try{
			Charcoal_ParamTrait::checkInteger( 1, $aggregate_func );
			Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_QueryTarget', $query_target );
			Charcoal_ParamTrait::checkIsA( 3, 'Charcoal_SQLCriteria', $criteria );
			Charcoal_ParamTrait::checkString( 4, $fields );

			// データソースの準備
			$this->initDataSource();
			// SQLビルダの準備
			$this->initSQLBuilder();

			// default count fields
			if ( $fields === NULL ){
				$fields = '*';
			}

			$current_model_name  = $query_target->getModelName();
			$current_model_alias = $query_target->getAlias();
			$current_model_joins = $query_target->getJoins();

			// get current model
			$model = $this->getModel( s($current_model_name) );

			// SQL
			$sql = $this->sql_builder->buildAggregateSQL( $aggregate_func, $model, $criteria, s($current_model_alias), v($current_model_joins), $fields );

			// パラメータ
			$params = $criteria->getParams();

//			log_debug( "debug,smart_gateway,sql", "sql:$sql" );
//			log_debug( "debug,smart_gateway,sql", "params:" . print_r($params,true) );

			// SQL実行
			$result = $this->data_source->prepareExecute( s($sql), v($params) );

			// フェッチ
			$rows = $this->data_source->fetchArray( $result );

			// result
			$result = $rows[0] ? intval($rows[0]) : 0;

			return $result;
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	get count of data in sigle table
	 */
	public  function count( Charcoal_String $query_target, Charcoal_SQLCriteria $criteria, Charcoal_String $fields = NULL ) 
	{
		try{
			Charcoal_ParamTrait::checkString( 1, $query_target );
			Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
			Charcoal_ParamTrait::checkString( 3, $fields );

			if ( $fields === NULL ){
				$fields = s('*');
			}

			// make query target list
			$query_target_list = new Charcoal_QueryTarget( $query_target );

			$result = $this->_executeAggregateSQL( Charcoal_EnumSQLAggregateFunc::FUNC_COUNT, $query_target_list, $criteria, $fields );

			log_debug( "debug,sql,smart_gateway", "smart_gateway", "COUNT result: $result" );

			return $result;
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	get max of data in sigle table
	 */
	public  function max( $query_target, $criteria, $fields ) 
	{
		try{
			Charcoal_ParamTrait::checkString( 1, $query_target );
			Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
			Charcoal_ParamTrait::checkString( 3, $fields );

			// make query target list
			$query_target_list = new Charcoal_QueryTarget( $query_target );

			$result = $this->_executeAggregateSQL( Charcoal_EnumSQLAggregateFunc::FUNC_MAX, $query_target_list, $criteria, $fields );

			log_debug( "debug,sql,smart_gateway", "smart_gateway", "MAX result: $result" );

			return $result;
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	get min of data in sigle table
	 */
	public  function min( $query_target, $criteria, $fields ) 
	{
		try{
			Charcoal_ParamTrait::checkString( 1, $query_target );
			Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
			Charcoal_ParamTrait::checkString( 3, $fields );

			// make query target list
			$query_target_list = new Charcoal_QueryTarget( $query_target );

			$result = $this->_executeAggregateSQL( Charcoal_EnumSQLAggregateFunc::FUNC_MIN, $query_target_list, $criteria, $fields );

			log_debug( "debug,sql,smart_gateway", "smart_gateway", "MIN result: $result" );

			return $result;
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	get sum of data in sigle table
	 */
	public  function sum( $query_target, $criteria, $fields ) 
	{
		try{
			Charcoal_ParamTrait::checkString( 1, $query_target );
			Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
			Charcoal_ParamTrait::checkString( 3, $fields );

			// make query target list
			$query_target_list = new Charcoal_QueryTarget( $query_target );

			$result = $this->_executeAggregateSQL( Charcoal_EnumSQLAggregateFunc::FUNC_SUM, $query_target_list, $criteria, $fields );

			log_debug( "debug,sql,smart_gateway", "smart_gateway", "SUM result: $result" );

			return $result;
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	get sum of data in sigle table
	 */
	public  function avg( $query_target, $criteria, $fields ) 
	{
		try{
			Charcoal_ParamTrait::checkString( 1, $query_target );
			Charcoal_ParamTrait::checkIsA( 2, 'Charcoal_SQLCriteria', $criteria );
			Charcoal_ParamTrait::checkString( 3, $fields );

			// make query target list
			$query_target_list = new Charcoal_QueryTarget( $query_target );

			$result = $this->_executeAggregateSQL( Charcoal_EnumSQLAggregateFunc::FUNC_AVG, $query_target_list, $criteria, $fields );

			log_debug( "debug,sql,smart_gateway", "smart_gateway", "AVG result: $result" );

			return $result;
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	ページ情報からLIMIT句で指定する値を生成
	 */
	public  function getLimit( $page_info ) 
	{
		try{
			Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_DBPageInfo', $page_info );

			// SQLビルダの準備
			$this->initSQLBuilder();

			// SQL
			$limit = $this->sql_builder->getLimit( $page_info );

			log_debug( "debug,smart_gateway,sql", "limit:$limit" );

			return $limit;
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	ページ情報からOFFSET句で指定する値を生成
	 */
	public  function getOffset( $page_info ) 
	{
		try{
			Charcoal_ParamTrait::checkIsA( 1, 'Charcoal_DBPageInfo', $page_info );

			// SQLビルダの準備
			$this->initSQLBuilder();

			// SQL
			$offset = $this->sql_builder->getOffset( $page_info );

			log_debug( "debug,smart_gateway,sql", "offset:$offset" );

			return $offset;
		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}


	/*
	 *	DBを作成
	 */
	public  function createDatabase( Charcoal_String $db_name, Charcoal_String $charset ) 
	{
		try{
			Charcoal_ParamTrait::checkString( 1, $db_name );
			Charcoal_ParamTrait::checkString( 2, $charset );

			// データソースの準備
			$this->initDataSource();
			// SQLビルダの準備
			$this->initSQLBuilder();

			// SQLビルダを使いSQLを生成
			$sql = $this->sql_builder->buildCreateDatabaseSQL( $db_name, $charset );

			log_debug( "debug,smart_gateway,sql", "sql:$sql" );

			// SQL実行
			$this->data_source->execute( s($sql) );

		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	テーブルを作成
	 */
	public  function createTable( $model_name ) 
	{
		try{
			Charcoal_ParamTrait::checkString( 1, $model_name );

			// データソースの準備
			$this->initDataSource();
			// SQLビルダの準備
			$this->initSQLBuilder();

			// テーブルモデル取得
			$model = $this->getModel( $model_name );

			// SQLビルダを使いSQLを生成
			$sql = $this->sql_builder->buildCreateTableSQL( $model );

			// SQL実行
			$this->data_source->execute( s($sql) );

		}
		catch ( Exception $e )
		{
			_catch( $e );
			_throw( new Charcoal_DBException( __METHOD__." Failed.", $e ) );
		}
	}

	/*
	 *	[Fluent Interface] create fluent interface
	 *
	 * @param string $fields    field list(comma separated string) for SELECT clause
	 *
	 * @return Charcoal_SelectContext    select context
	 */
	public  function select( $fields ) 
	{
		Charcoal_ParamTrait::checkString( 1, $fields );

		$context = new Charcoal_QueryContext( $this );

		if ( !$fields->isEmpty() ){
			$fields = $fields->split( s(',') );
			$context->setFields( $fields );
		}

		return new Charcoal_SelectContext( $context );
	}
}


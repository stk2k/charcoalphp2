<?php
/**
* data source for SQLite
*
* PHP version 5
*
* @package    objects.data_sources
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_SQLiteDataSource extends Charcoal_AbstractDataSource
{
    private $connected = false;
    private $connection;

    private $db_file;

    private $trans_cnt;

    /*
     *    コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();

        $this->connected     = false;
        $this->connection     = null;
        $this->command_id     = 0;
    }

    /**
     * Initialize instance
     *
     * @param Charcoal_Config $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );

        $this->db_file   = $config->getString( 'db_file' );

    }

    /*
     *    接続済みか
     */
    public function isConnected()
    {
        return $this->connected;
    }

    /*
     *    自動コミット機能をON/OFF
     */
    public function autoCommit( $on )
    {
        _throw( new Charcoal_NotSupportedOperationException( 'autoCommit' ) );
    }

    /*
     *    トランザクションを開始
     */
    public function beginTrans()
    {
        _throw( new Charcoal_NotSupportedOperationException( 'beginTrans' ) );
    }

    /*
     *    コミットを発行
     */
    public function commitTrans()
    {
        _throw( new Charcoal_NotSupportedOperationException( 'commitTrans' ) );
    }

    /*
     *    ロールバックを発行
     */
    public function rollbackTrans()
    {
        _throw( new Charcoal_NotSupportedOperationException( 'rollbackTrans' ) );
    }

    /*
     *    接続
     */
    public function connect( $force = FALSE )
    {
        // 接続済みなら何もしない
        if ( $this->connected && !$force ){
            return;
        }

        $db_file   = $this->db_file;

        try{
            $sqliteerror = '';
            $this->connection = sqlite_open($db_file, 0666, $sqliteerror);
        }
        catch ( Exception $e )
        {
            _catch( $e );

            _throw( new Charcoal_DBConnectException( __METHOD__ . " failed: [db_string]$db_string", $e ) );
        }

    }

    /*
     *    接続を閉じる
     */
    public function disconnect()
    {
        // 接続していないなら何もしない
        if ( !$this->connected ){
            return;
        }

        // 切断
        sqlite_close( $this->connection );
        $this->connection = NULL;

        $this->connected = FALSE;
    }

    /*
     *    SQLをそのまま発行（結果セットあり）
     */
    public function query( $sql )
    {
        Charcoal_ParamTrait::validateString( 1, $sql );

        // 接続処理
        $this->connect();

        // SQLを実行して結果セットを得る
        $sqliteerror = '';
        $result = $this->sqlite_query( $sql, SQLITE_BOTH, $sqliteerror);
        if ( $result === FALSE ){
            _throw( new Charcoal_DBDataSourceException( __METHOD__." Failed. SQLite error: $sqliteerror" ) );
        }

        // 結果セットを返却
        return $result;
    }

    /*
     *    SQLをそのまま発行（結果セットなし）
     */
    public function execute( $sql )
    {
        Charcoal_ParamTrait::validateString( 1, $sql );

        // 接続処理
        $this->connect();
 
        // SQLを実行
        $sqliteerror = '';
        $result = $this->sqlite_exec( $sql, SQLITE_BOTH, $sqliteerror);
        if ( $result === FALSE ){
            _throw( new Charcoal_DBDataSourceException( __METHOD__." Failed. SQLite error: $sqliteerror" ) );
        }
    }

    /*
     *    実行結果件数取得
     */
    function numRows( $stmt )
    {
        return $stmt->numRows();
    }

    /*
     *    フェッチ処理（連想配列で返却）
     */
    public function fetchAssoc( $stmt )
    {
        return $stmt->fetchAll( SQLITE_ASSOC );
    }

    /*
     *    フェッチ処理（配列で返却）
     */
    public function fetchArray( $stmt )
    {
        return $stmt->fetch( SQLITE_NUM );
    }

    /*
     *   最後に実行されたAUTO_INCREMENT値を取得
     */
    public function getLastInsertId()
    {
        return $this->connection ? $this->connection->lastInsertRowid() : -1;
    }

}


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

class DmlTestTask extends Charcoal_TestTask
{
    const DB_HOST = '127.0.0.1';
    const DB_NAME = 'charcoal';
    const DB_USER = 'root';
    const DB_PASS = '';

    private $pdo;

    /**
     * check if action will be processed
     */
    public function isValidAction( $action )
    {
        switch( $action ){
        case "dml_truncate":
        case "dml_bulk_insert":
            return TRUE;
        }
        return FALSE;
    }

    /**
     * セットアップ
     */
    public function setUp( $action, $context )
    {
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false
        );

        $dsn = 'mysql:dbname='.self::DB_NAME.';host=' . self::DB_HOST . ';charset=utf8';
        try {
            $this->pdo = new PDO($dsn, self::DB_USER, self::DB_PASS, $options);

            // testテーブルを全削除
            $sql = 'truncate table test';
            $this->pdo->prepare($sql)->execute();

            // テストデータ挿入
            $sql = 'insert into test(name, price) values (?, ?)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array('扇風機', 3400));

        } catch (PDOException $e){
            echo $e->getMessage();
        }
    }

    /**
     * クリーンアップ
     */
    public function cleanUp( $action, $context )
    {
    }

    /**
     * テーブルのデータ数をカウント
     */
    public function countTableRows( $table )
    {
        try {
            $sql = 'select count(*) from ' . $table;
            $result = $this->pdo->query($sql)->fetch(PDO::FETCH_NUM);

            return $result[0];
        } catch (PDOException $e){
            echo $e->getMessage();
        }
    }

    /**
     * SQLのデータ数をカウント
     */
    public function countRows( $table, $where )
    {
        try {
            $sql = 'select count(*) from ' . $table . ' where ' . $where;
            $result = $this->pdo->query($sql)->fetch(PDO::FETCH_NUM);

            return $result[0];
        } catch (PDOException $e){
            echo $e->getMessage();
        }
    }

    /**
     * テスト
     */
    public function test( $action, $context )
    {
        $action = us($action);

        // SmartGateway
        $gw = $context->getComponent( 'smart_gateway@:charcoal:db' );

        switch( $action ){
        case "dml_truncate":
            $rows = $this->countTableRows('test');

            $this->assertEquals( 1, $rows );

            $gw->truncateTable( __FILE__ . '(' . __LINE__ . ')', 'test' );

            $rows = $this->countTableRows('test');

            $this->assertEquals( 0, $rows );

            return TRUE;

        case "dml_bulk_insert":
            // テーブルの行数は1になっているはず
            $this->assertEquals( 1, $this->countTableRows('test'));
            // appleの行数は0になっているはず
            $this->assertEquals( 0, $this->countRows('test', 'name = "apple"') );
            // grapeの行数は0になっているはず
            $this->assertEquals( 0, $this->countRows('test', 'name = "grape"') );
            // orageの行数は0になっているはず
            $this->assertEquals( 0, $this->countRows('test', 'name = "orage"') );
            // melonの行数は0になっているはず
            $this->assertEquals( 0, $this->countRows('test', 'name = "melon"') );

            // apple/grape/orage/melonのデータをバルクインサート
            $data_set = array(
                    new Charcoal_DTO( ['name' => 'apple', 'price' => 100 ] ),
                    new Charcoal_DTO( ['name' => 'grape', 'price' => 200 ] ),
                    new Charcoal_DTO( ['name' => 'orage', 'price' => 150 ] ),
                    new Charcoal_DTO( ['name' => 'melon', 'price' => 1800 ] ),
                );

            $gw->insertAll( __FILE__ . '(' . __LINE__ . ')', 'test', $data_set );

            // テーブルの行数は5になっているはず
            $this->assertEquals( 5, $this->countTableRows('test'));
            // appleの行数は1になっているはず
            $this->assertEquals( 1, $this->countRows('test', 'name = "apple"') );
            // grapeの行数は1になっているはず
            $this->assertEquals( 1, $this->countRows('test', 'name = "grape"') );
            // orageの行数は1になっているはず
            $this->assertEquals( 1, $this->countRows('test', 'name = "orage"') );
            // melonの行数は1になっているはず
            $this->assertEquals( 1, $this->countRows('test', 'name = "melon"') );


            return TRUE;
        }

        return FALSE;
    }

}

return __FILE__;
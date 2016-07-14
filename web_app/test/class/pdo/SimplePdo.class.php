<?php
/**
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class SimplePdo extends PDO
{
    const TAG = 'simple_pdo';

    /**
     * constructor
     *
     * @param array $config
     * @param array $options
     */
    public function __construct($config, $options = array())
    {
        $config = new Charcoal_HashMap($config);

        // 接続情報を取得
        $backend = $config->getString('backend');
        $server = $config->getString('server');
        $user = $config->getString('user');
        $password = $config->getString('password');
        $db_name = $config->getString('db_name');
        $port = $config->getString('port');
        $charset = $config->getString('charset');

        //ad($config->getAll());

        // PDOオブジェクトを作成
        $dsn = "$backend:host=$server;{$port}dbname=$db_name;charset={$charset};";
        //echo "DSN:$dsn" . PHP_EOL;
        $default_options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        );
        $options = array_merge($default_options, $options);
        parent::__construct( $dsn, $user, $password, $options );
    }

    /**
     * get one value
     *
     * @param Charcoal_String|string $sql
     * @param array $params
     *
     * @return mixed
     */
    public function queryValue( $sql, $params = array() ){

        $stmt = parent::prepare($sql);
        if ( $stmt === FALSE ){
            throw new RuntimeException("prepare SQL failed: $sql");
        }

        $result = $stmt->execute($params);
        if ( $result === FALSE ){
            throw new RuntimeException("executing SQL failed: $sql");
        }

        $result = $stmt->fetchAll();
        if ( $result === FALSE ){
            throw new RuntimeException("fetchAll failed: $sql");
        }

        return isset($result[0][0]) ? $result[0][0] : '';
    }

    /**
     * execute query and return selected rows
     *
     * @param Charcoal_String|string $sql
     * @param array $params
     *
     * @return array rows selected
     */
    public function queryRows( $sql, $params = array() ){

        $stmt = parent::prepare($sql);
        if ( $stmt === FALSE ){
            throw new RuntimeException("prepare SQL failed: $sql");
        }

        $result = $stmt->execute($params);
        if ( $result === FALSE ){
            throw new RuntimeException("executing SQL failed: $sql");
        }

        $rows = $stmt->fetchAll();
        if ( $rows === FALSE ){
            throw new RuntimeException("fetchAll failed: $sql");
        }

        return $rows;
    }

    /**
     * execute query and return a selected row
     *
     * @param Charcoal_String|string $sql
     * @param array $params
     *
     * @return array|null rows selected
     */
    public function queryRow( $sql, $params = array() ){

        $stmt = parent::prepare($sql);
        if ( $stmt === FALSE ){
            throw new RuntimeException("prepare SQL failed: $sql");
        }

        $result = $stmt->execute($params);
        if ( $result === FALSE ){
            throw new RuntimeException("executing SQL failed: $sql");
        }

        $rows = $stmt->fetchAll();
        if ( $rows === FALSE ){
            throw new RuntimeException("fetchAll failed: $sql");
        }

        return isset($rows[0]) ? $rows[0] : null;
    }
}

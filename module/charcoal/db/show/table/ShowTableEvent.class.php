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

class ShowTableEvent extends Charcoal_Event
{
    private $database;
    private $table;

    /**
     *    constructor
     *
     *    @param string|Charcoal_String $database         database name
     *    @param string|Charcoal_String $table          table name
     */
    public function __construct( $database, $table )
    {
        parent::__construct();

        $this->database       = us($database);
        $this->table        = us($table);
    }

    /**
     * get database name
     *
     * @return string    database name
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * get table name
     *
     * @return string    table name
     */
    public function getTable()
    {
        return $this->table;
    }


}

return __FILE__;
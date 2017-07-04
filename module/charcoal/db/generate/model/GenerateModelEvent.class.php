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

class GenerateModelEvent extends Charcoal_Event
{
    private $database;
    private $table;
    private $target_dir;

    /**
     *    constructor
     *
     *    @param string|Charcoal_String $database         database name
     *    @param string|Charcoal_String $table          table name
     *    @param string|Charcoal_String $target_dir     target directory
     */
    public function __construct( $database, $table, $target_dir )
    {
        parent::__construct();

        $this->database     = us($database);
        $this->table        = us($table);
        $this->target_dir   = us($target_dir);
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

    /**
     * get target directory
     *
     * @return string    target directory
     */
    public function getTargetDir()
    {
        return $this->target_dir;
    }

}

return __FILE__;
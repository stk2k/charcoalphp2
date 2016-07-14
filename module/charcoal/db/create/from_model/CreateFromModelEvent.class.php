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

class CreateFromModelEvent extends Charcoal_Event
{
    private $database;

    /**
     *    constructor
     *
     *    @param string|Charcoal_String $database         database name
     */
    public function __construct( $database )
    {
        parent::__construct();

        $this->database       = us($database);
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

}

return __FILE__;
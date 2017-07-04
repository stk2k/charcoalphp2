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

class CompileQueryTargetEvent extends Charcoal_Event
{
    private $query_target;

    /**
     *    constructor
     *
     *    @param string|Charcoal_String $query_target
     */
    public function __construct( $query_target )
    {
        parent::__construct();

        $this->query_target    = us($query_target);
    }

    /**
     * get query target
     *
     * @return string
     */
    public function getQueryTarget()
    {
        return $this->query_target;
    }

}

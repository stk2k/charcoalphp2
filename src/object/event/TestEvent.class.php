<?php
/**
* Test Event
*
* PHP version 5
*
* @package    objects.events
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_TestEvent extends Charcoal_UserEvent 
{
    private $section;
    private $target;
    private $actions;

    /**
     *    Constructor
     *
     * @param string $section       test category name for description
     * @param array $actions        action list
     * @param string $target        target object path
     */
    public function __construct( $section, $target, $actions )
    {
        parent::__construct();

        $this->section = $section;
        $this->target = $target;
        $this->actions = $actions;
    }

    /**
     *    Get section name
     *
     * @return array     action list
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     *    Get target object path
     *
     * @return array     action list
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     *    Get action list
     *
     * @return array     action list
     */
    public function getActions()
    {
        return $this->actions;
    }

    /*
     *  String expression of this object
     *
     * @return string
     */
    public function toString()
    {
        $actions = strlen($this->actions) > 20 ? substr($this->actions,0,20) . '...' : $this->actions;
        return $this->getObjectName() . "[$actions]";
    }
}


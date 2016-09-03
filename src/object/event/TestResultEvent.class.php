<?php
/**
* Test Result Event
*
* PHP version 5
*
* @package    objects.events
* @author     CharcoalPHP Development Team
* @copyright  2016 stk2k, sazysoft
*/

class Charcoal_TestResultEvent extends Charcoal_UserEvent
{
    private $section;
    private $action;
    private $success;

    /**
     *    Constructor
     *
     * @param string|Charcoal_String $section
     * @param string|Charcoal_String $action
     * @param boolean|Charcoal_Boolean $success       If TRUE, the test succeeded
     */
    public function __construct( $section, $action, $success )
    {
        parent::__construct();

        $this->section = us($section);
        $this->action = us($action);
        $this->success = ub($success);
    }

    /**
     *    Get section
     *
     * @return string
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     *    Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     *    Get success
     *
     * @return boolean
     */
    public function getSuccess()
    {
        return $this->success;
    }

}


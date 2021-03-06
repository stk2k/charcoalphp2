<?php
/**
* base class for task
*
* PHP version 5
*
* @package    objects.tasks
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

abstract class Charcoal_Task extends Charcoal_CharcoalComponent implements Charcoal_ITask
{
    const TAG = "Task";

    private $name_space;
    private $guard_conditions;
    private $event_filters;
    private $post_actions;
    private $priority;

    /**
     * Initialize instance
     *
     * @param array $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );
        
        $config = new Charcoal_HashMap($config);

        $this->name_space         = $config->getString( 'name_space', '' );
        $this->event_filters      = $config->getArray( 'event_filters', array() );
        $this->post_actions       = $config->getArray( 'post_actions', array('remove_event') );
        $this->priority           = $config->getInteger( 'priority', 0 );

        if ( $this->getSandbox()->isDebug() )
        {
            log_debug( "debug,event", "Task[$this] name space: {$this->name_space}", self::TAG );
            log_debug( "debug,event", "Task[$this] event filters: " . $this->event_filters, self::TAG );
            log_debug( "debug,event", "Task[$this] post actions: " . $this->post_actions, self::TAG );
            log_debug( "debug,event", "Task[$this] priority: {$this->priority}", self::TAG );
        }
    }

    /**
     * handle an exception
     *
     * @param Exception $e                        exception to handle
     * @param Charcoal_EventContext $context      event context
     *
     * @return boolean|Charcoal_Boolean
     */
    public function handleException( $e, $context )
    {
        return FALSE;
    }

    /**
     * get name space
     *
     * @return string      name space of this task
     */
    public function getNameSpace()
    {
        return $this->name_space;
    }

    /**
     * se name space
     *
     * @param array $name_space      name space of this task
     */
    public function setNameSpace( $name_space )
    {
        $this->name_space = $name_space;
    }

    /**
     * get guard conditions
     *
     * @return array      guard conditions of this task
     */
    public function getGuardConditions()
    {
        return $this->guard_conditions;
    }

    /**
     * set guard conditions
     *
     * @param array $guard_conditions      guard conditions of this task
     */
    public function setGuardConditions( $guard_conditions )
    {
        $this->guard_conditions = $guard_conditions;
    }

    /**
     * get event filters
     *
     * @return array      event filters of this task
     */
    public function getEventFilters()
    {
        return $this->event_filters;
    }

    /**
     * se event filters
     *
     * @param array $event_filters      event filters of this task
     */
    public function setEventFilters( $event_filters )
    {
        $this->event_filters = $event_filters;
    }

    /**
     * get post actions
     *
     * @return array      post actions of this task
     */
    public function getPostActions()
    {
        return $this->post_actions;
    }

    /**
     * add a post action
     *
     * @return array      post action t oadd
     */
    public function addPostAction( $action )
    {
        $this->post_actions->add( $action );
    }

    /**
     * remove a post action
     *
     * @return array      post action to remove
     */
    public function removePostAction( $action )
    {
        $key = array_search( $action, $this->post_actions );
        if ( $key !== FALSE ){
            unset( $this->post_actions[$key]);
        }
    }

    /**
     * set post actions
     *
     * @param array $post_actions      post actions of this task
     */
    public function setPostActions( $post_actions )
    {
        $this->post_actions = v($post_actions);
    }

    /**
     * get priority
     *
     * @return integer      priority of this task
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * set priority
     *
     * @param integer $priority      priority of this task
     */
    public function setPriority( $priority )
    {
        $this->priority = $priority;
    }

    /*
     *  String expression of this object
     *
     * @return string
     */
    public function toString()
    {
        return $this->getObjectName() ? $this->getObjectName() : '(new)';
    }
}


<?php
/**
* タスクマネージャを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

interface Charcoal_ITaskManager extends Charcoal_ICharcoalObject
{
    /**
     * refister task
     *
     * @param Charcoal_String|string $key
     * @param Charcoal_ITask $task
     */
    public function registerTask( $key, $task );

    /**
     * unregister task
     *
     * @param Charcoal_String|string $key
     */
    public function unregisterTask( $key );

    /**
     * get task
     *
     * @param Charcoal_String|string $key
     *
     * @return Charcoal_ITask
     *
     * @throws Charcoal_TaskNotFoundException
     */
    public function getTask( $key );

    /**
     * Get event queue
     *
     * @return Charcoal_IEventQueue       event queue object
     */
    public function getEventQueue();

    /**
     *  add an event to task manager
     *
     * @param Charcoal_IEvent $event
     *
     */
    public function pushEvent( $event );

    /**
     *   process events
     *
     * @param Charcoal_IEventContext $context
     *
     * @return int
     */
    public function processEvents( $context );

    /**
     *   save statefull task
     *
     * @param Charcoal_Session $session
     *
     */
    public function saveStatefulTasks( $session );

    /**
     *   restore stateful task
     *
     * @param Charcoal_Session $session
     *
     */
    public function restoreStatefulTasks( $session );
}


<?php

use EventStream\IEventSource;
use \EventStream\Exception\EventSourceIsNotPushableException;

class Charcoal_CoreHookEventSource implements IEventSource
{
    private $sandbox;
    private $events;
    
    /**
     *  Constructor
     *
     * @param Charcoal_Sandbox $sandbox
     */
    public function __construct( $sandbox ) {
        $this->sandbox = $sandbox;
        $this->events = array();
    }
    
    /**
     * check if event source can be pushed a event
     *
     * @param string $event
     *
     * @return bool     true if pushable, false if the event store can not be pushed
     */
    public function canPush($event) {
        return true;
    }
    
    /**
     * store event
     *
     * @param string $event
     * @param array|null $args
     *
     * @throws EventSourceIsNotPushableException, OverflowException
     * @return IEventSource
     */
    public function push($event, $args=null) {
        $this->events[] = array($event, $args);
        return $this;
    }
    
    /**
     * generate next event
     *
     * @return array|string|null       array($event, $args) or $event or null if no events remain in event source.
     */
    public function next() {
        return array_shift($this->events);
    }
}
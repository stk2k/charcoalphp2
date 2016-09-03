<?php
/**
* Event Context Interface
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IQueue
{
    /*
     * Take item from the queue
     *
     * @return mixed      item
     */
    public function dequeue();

    /*
     * Add item to the queue
     *
     * @param mixed $item       item to add
     */
    public function enqueue( $item );

    /*
     * Checks whether the queue is empty.
     *
     * @return boolean    whether the queue is empty.
     */
    public function isEmpty();

}


<?php
/**
* キュークラス
*
* PHP version 5
*
* @package    class.core
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_Queue extends Charcoal_Vector implements Charcoal_IQueue
{
	/*
	 * Take item from the queue
	 *
	 * @return Charcoal_IEvent      event
	 */
	public function dequeue()
	{
		return $this->shift();
	}

	/*
	 * Add item to the queue
	 *
     * @param Charcoal_IEvent $event       event to add
	 */
	public function enqueue( $item )
	{
		$this->add( $item );
	}
}


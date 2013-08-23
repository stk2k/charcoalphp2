<?php
/**
* イベントのキューを扱うクラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_EventQueue extends Charcoal_Queue
{
	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/*
	 * イベントをキューに追加する
	 *
	 */
	public function enqueueEvent( Charcoal_IEvent $event )
	{
		parent::enqueue( $event );
	}

	/*
	 * イベントのキューを優先度でソートする
	 *
	 */
	public function sortByPriority()
	{
		$array = $this->getValue();

		if ( !$array ){
			return;
		}

		$key_priority = NULL;
		foreach ( $array as $key => $event ){
			$key_priority[$key] = ui( $event->getPriority() );
		}
		array_multisort( $key_priority,SORT_DESC, $array );

		$this->setValue( $array );
	}
}
return __FILE__;

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
	/*
	 * タスクを登録する
	 */
	public function registerTask( Charcoal_String $key, Charcoal_ITask $task );

	/*
	 * タスクを登録を解除する
	 */
	public function unregisterTask( Charcoal_String $key );

	/*
	 * タスクを取得する
	 */
	public function getTask( Charcoal_String $task_name );

	/**
	 *  add an event to task manager
	 *
	 */
	public function pushEvent( Charcoal_IEvent $event );

	/**
	 *   イベント処理を行う
	 *
	 */
	public function processEvents( Charcoal_IEventContext $context );

	/**
	 *   ステートフルタスクの保存を行う
	 *
	 */
	public function saveStatefulTasks( Charcoal_Session $session );

	/**
	 *   ステートフルタスクの復帰を行う
	 *
	 */
	public function restoreStatefulTasks( Charcoal_Session $session );
}


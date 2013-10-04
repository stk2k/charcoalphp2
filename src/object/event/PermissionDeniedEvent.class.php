<?php
/**
* イベントクラス
*
* PHP version 5
*
* @package    objects.events
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_PermissionDeniedEvent extends Charcoal_UserEvent implements Charcoal_IEvent
{
	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();
	}


}


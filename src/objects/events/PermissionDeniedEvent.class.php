<?php
/**
* イベントクラス
*
* PHP version 5
*
* @package    events
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
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


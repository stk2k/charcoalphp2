<?php

class Charcoal_SessionTableModel extends Charcoal_DefaultTableModel
{
	var $___table_name      = 'sessions';

	var $id                 = '@field @type:bigint @pk @insert:no @update:no @insert:no @serial';
	var $session_id         = '@field @type:varchar(255) @insert:value @update:value ';
	var $session_data       = '@field @type:text @insert:value @update:value ';

	/*
	 *   テーブル固有のDTOを作成
	 */
	public function createDTO()
	{
		return new SessionDTO();
	}
}

return __FILE__;

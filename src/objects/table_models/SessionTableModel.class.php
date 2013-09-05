<?php

class Charcoal_SessionTableModel extends Charcoal_DefaultTableModel
{
	public $___table_name      = 'sessions';

	public $id                 = '@field @type:bigint @pk @insert:no @update:no @insert:no @serial';
	public $session_id         = '@field @type:text @insert:value @update:value ';
	public $save_path          = '@field @type:text @insert:value @update:value ';
	public $session_name       = '@field @type:text @insert:value @update:value ';
	public $session_data       = '@field @type:longtext @insert:value @update:value ';
    public $created            = '@field @type:datetime @insert:function[now] @update:no';
    public $modified           = '@field @type:datetime @insert:function[now] @update:function[now]';

	/**
	 *  returns own table DTO
	 */
	public function createDTO()
	{
		return new Charcoal_SessionTableDTO();
	}
}

<?php
/**
* DTO for session
*
* PHP version 5
*
* @package    objects.DTOs
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_SessionTableDTO extends Charcoal_TableDTO
{
    public $id;
    public $session_id;
    public $save_path;
    public $session_name;
    public $session_data;
    public $created;
    public $modified;
}


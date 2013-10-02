<?php
/**
 *   (Auto Generated Class)
 *   PostTableModel class
 *   
 *   generated by CharcoalPHP ver.2.8.18.95
 *   
 *   PHP version 5
 *   
 *   @author     CharcoalPHP Development Team
 *   @copyright  2008 - 2013 CharcoalPHP Development Team
 */
class PostTableModel extends Charcoal_DefaultTableModel
{
    public $___table_name      = 'posts';

	public $comments           = '@relation @target:comments @linkage:outer[comment_id] @extract:array';

    public $post_id            = '@field @type:int(11) @pk @insert:no @update:no @serial';
    public $blog_id            = '@field @type:int(11) @insert:value @update:value @fk:blogs';
    public $post_title         = '@field @type:text @insert:value @update:value';
    public $post_body          = '@field @type:text @insert:value @update:value';
    public $post_user          = '@field @type:vachar(255) @insert:value @update:value';
    public $favorite           = '@field @type:int(11) @insert:value @update:value';

	// returns model's own DTO
    public function createDTO( $values = array() )
    {
        return new PostTableDTO( $values );
    }
}

return __FILE__;	// end of file
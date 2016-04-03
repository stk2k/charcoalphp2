<?php
/**
 *   (Auto Generated Class)
 *   BlogTableModel class
 *   
 *   generated by CharcoalPHP ver.2.8.18.95
 *   
 *   PHP version 5
 *   
 *   @author     CharcoalPHP Development Team
 *   @copyright  2008 - 2013 CharcoalPHP Development Team
 */
class BlogTableModel extends Charcoal_DefaultTableModel
{
    public $___table_name     = 'blogs';

    public $posts             = '@relation @target:posts @linkage:outer[blog_id] @extract:array';
//    public $blog_category     = '@relation @target:blog_category @linkage:inner[blog_category_id] @extract:field';

    public $blog_id           = '@field @type:int(11) @pk @insert:no @update:no @serial';
    public $blog_category_id  = '@field @type:int(11) @insert:no @update:no @fk:blog_category';
    public $blog_name         = '@field @type:vachar(255) @insert:value @update:value';
    public $post_total        = '@field @type:int(11) @insert:value @update:value';
    public $created_date      = '@field @type:datetime @insert:value @update:value';
    public $modified_date     = '@field @type:datetime @insert:value @update:value';

    // returns model's own DTO
    public function createDTO( $values = array() )
    {
        return new BlogTableDTO( $values );
    }
}

return __FILE__;    // end of file

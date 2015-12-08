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
class BlogCategoryTableModel extends Charcoal_DefaultTableModel
{
    public $___table_name     = 'blog_category';

    public $blog_category_id    = '@field @type:int(11) @pk @insert:no @update:no @serial';
    public $blog_category_name  = '@field @type:vachar(255) @insert:value @update:value';

    // returns model's own DTO
    public function createDTO( $values = array() )
    {
        return new BlogCategoryTableDTO( $values );
    }
}

return __FILE__;    // end of file

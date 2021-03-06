<?php
/**
 *   (Auto Generated Class)
 *   ItemTableModel class
 *   
 *   generated by CharcoalPHP ver.2.64.1.258
 *   
 *   @author     your name
 *   @copyright  
 */
class ItemTableModel extends Charcoal_DefaultTableModel
{
    public $___table_name      = 'item';

    public $item_id                       = '@field @type:int(11) @pk @insert:no @update:no @serial';
    public $item_name                     = '@field @type:text @insert:value @update:value';
    public $price                         = '@field @type:int(11) @insert:value @update:value';
    public $stock                         = '@field @type:int(11) @insert:value @update:value';
    public $created_date                  = '@field @type:datetime @insert:value @update:value';
    public $modified_date                 = '@field @type:datetime @insert:value @update:value';

	// returns model's own DTO
    public function createDTO( $values = array() )
    {
        return new ItemTableDTO( $values );
    }
}

return __FILE__;    // end of file

<?php
/**
* タスク
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class ModelGenerateTask extends Charcoal_Task
{
    const DIR_MODE        = '666';
    const SPACE_COUNT     = 30;

    /**
     * process event
     *
     * @param Charcoal_IEventContext $context
     *
     * @return boolean|Charcoal_Boolean
     */
    public function processEvent( $context )
    {
        /** @var ModelGenerateEvent $event */
        $event   = $context->getEvent();

        // パラメータを取得
        $table         = $event->getTable();
        $out_dir       = $event->getTargetDir();

        $entity = $table;

        $entity = Charcoal_System::pascalCase( $table );
        $config_key = Charcoal_System::snakeCase( $table );

        $table_model_class_name = "{$entity}TableModel";
        $table_dto_class_name = "{$entity}TableDTO";
        $date_now_y = date("Y");

        /** @var Charcoal_SmartGateway $gw */
        $gw = $context->getComponent( 'smart_gateway@:charcoal:db' );

        //=======================================
        // Mmake output directory
        //=======================================
        $out_dir = new Charcoal_File( $out_dir );

        $out_dir->makeDirectory( self::DIR_MODE );

        //=======================================
        // Genarate  table model file
        //=======================================

        $colmn_attr_list = $gw->query( NULL, 'SHOW COLUMNS FROM ' . $table );

        $lines = NULL;

        $lines[] = "<?php";
        $lines[] = "/**";
        $lines[] = " *   (Auto Generated Class)";
        $lines[] = " *   {$table_model_class_name} class";
        $lines[] = " *   ";
        $lines[] = " *   generated by CharcoalPHP ver." . Charcoal_Framework::getVersion();
        $lines[] = " *   ";
        $lines[] = " *   @author     your name";
        $lines[] = " *   @copyright  ";
        $lines[] = " */";
        $lines[] = "class {$table_model_class_name} extends Charcoal_DefaultTableModel";
        $lines[] = "{";
        $lines[] = "    public \$___table_name      = '{$table}';";
        $lines[] = "";

        foreach( $colmn_attr_list as $colmn_attr ){
            $field     = $colmn_attr['Field'];
            $type      = $colmn_attr['Type'];
            $null      = $colmn_attr['Null'];
            $key       = $colmn_attr['Key'];
            $default   = $colmn_attr['Default'];
            $extra     = $colmn_attr['Extra'];

            $spaces = str_repeat( " ", self::SPACE_COUNT - strlen($field) );

            if ( $key == "PRI" ){
                $lines[] = "    public \${$field}{$spaces}= '@field @type:$type @pk @insert:no @update:no @serial';";
            }
            else{
                $lines[] = "    public \${$field}{$spaces}= '@field @type:$type @insert:value @update:value';";
            }
//echo "Field:$field Type:$type Null:$null Key:$key Default:$default Extra:$extra" . PHP_EOL;
        }

        $lines[] = "";
        $lines[] = "\t// returns model's own DTO";
        $lines[] = "    public function createDTO( \$values = array() )";
        $lines[] = "    {";
        $lines[] = "        return new {$table_dto_class_name}( \$values );";
        $lines[] = "    }";
        $lines[] = "}";
        $lines[] = "";
        $lines[] = "return __FILE__;    // end of file";

        $file_name = $table_model_class_name . ".class.php";
        $outfile = new Charcoal_File( $file_name, $out_dir );
        Charcoal_FileSystemUtil::outputFile( $outfile, $lines );

        print "{$outfile} was successfully generated." . PHP_EOL;

        //=======================================
        // Genarate table DTO file
        //=======================================

        $lines = NULL;

        $lines[] = "<?php";
        $lines[] = "/**";
        $lines[] = " *   (Auto Generated Class)";
        $lines[] = " *   {$table_dto_class_name} class";
        $lines[] = " *   ";
        $lines[] = " *   generated by CharcoalPHP ver." . Charcoal_Framework::getVersion();
        $lines[] = " *   ";
        $lines[] = " *   PHP version 5";
        $lines[] = " *   ";
        $lines[] = " *   @author     CharcoalPHP Development Team";
        $lines[] = " *   @copyright  2008 stk2k, sazysoft";
        $lines[] = " */";
        $lines[] = "class {$table_dto_class_name} extends Charcoal_TableDTO";
        $lines[] = "{";

        foreach( $colmn_attr_list as $colmn_attr ){
            $field     = $colmn_attr['Field'];
            $type      = $colmn_attr['Type'];
            $null      = $colmn_attr['Null'];
            $key       = $colmn_attr['Key'];
            $default   = $colmn_attr['Default'];
            $extra     = $colmn_attr['Extra'];

            $lines[] = "    public \${$field};";
        }

        $lines[] = "}";
        $lines[] = "";
        $lines[] = "return __FILE__;    // end of file";

        $file_name = $table_dto_class_name . ".class.php";
        $outfile = new Charcoal_File( $file_name, $out_dir );
        Charcoal_FileSystemUtil::outputFile( $outfile, $lines );

        print "{$outfile} was successfully generated." . PHP_EOL;

        //=======================================
        // Genarate  config file
        //=======================================

        $lines = NULL;

        $lines[] = "class_name    = {$table_model_class_name}";

        $file_name = $config_key . ".table_model.ini";
        $outfile = new Charcoal_File( $file_name, $out_dir );
        Charcoal_FileSystemUtil::outputFile( $outfile, $lines );

        print "{$outfile} was successfully generated." . PHP_EOL;

        return b(true);
    }
}

return __FILE__;
<?php
/**
* Task for showing table information
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class ShowTableTask extends Charcoal_Task
{
    /**
     * process event
     *
     * @param Charcoal_IEventContext $context
     *
     * @return boolean|Charcoal_Boolean
     */
    public function processEvent( $context )
    {
        /** @var ShowTableEvent $event */
        $event   = $context->getEvent();

        // get event parameters
        $db_name       = $event->getDatabase();
        $table_name    = $event->getTable();

        /** @var Charcoal_SmartGateway $gw */
        $gw = $context->getComponent( 'smart_gateway@:charcoal:db' );

        //=======================================
        // confirm if the table exists
        //=======================================
        $sql = "SELECT count(*) FROM information_schema.COLUMNS WHERE TABLE_NAME = ? AND TABLE_SCHEMA = ? ";
        $params = array( $table_name, $db_name );
        $count = $gw->queryValue( NULL, $sql, $params );

        if ( $count < 1 ){
            print "[ERROR] Specified table '$table_name' does not exist in schema: '$db_name'. Maybe table name is wrong?" . PHP_EOL;
            return b(true);
        }

        print "Showing table information." . PHP_EOL . PHP_EOL;

        print "=========================================" . PHP_EOL;
        print "Table description: $table_name" . PHP_EOL . PHP_EOL;

        //=======================================
        // Retrieve column information
        //=======================================
        $sql = "SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_KEY, COLUMN_DEFAULT, EXTRA ";
        $sql .= " FROM information_schema.COLUMNS WHERE TABLE_NAME = ? AND TABLE_SCHEMA = ? ";
        $params = array( $table_name, $db_name );
        $colmn_attr_list = $gw->query( NULL, $sql, $params );

        // get max length
        $field_max_width = $this->getMaxLengthOfMeta( $colmn_attr_list, 'COLUMN_NAME' );
        $type_max_width = $this->getMaxLengthOfMeta( $colmn_attr_list, 'COLUMN_TYPE' );
        $null_max_width = $this->getMaxLengthOfMeta( $colmn_attr_list, 'IS_NULLABLE' );
        $key_max_width = $this->getMaxLengthOfMeta( $colmn_attr_list, 'COLUMN_KEY' );
        $default_max_width = $this->getMaxLengthOfMeta( $colmn_attr_list, 'COLUMN_DEFAULT' );
        $extra_max_width = $this->getMaxLengthOfMeta( $colmn_attr_list, 'EXTRA' );

        print str_pad( "Field Name", $field_max_width+1 ) . " ";
        print str_pad( "Type", $type_max_width+1 ) . " ";
        print str_pad( "Null", $null_max_width+1 ) . " ";
        print str_pad( "Key", $key_max_width+1 ) . " ";
        print str_pad( "Default", $default_max_width+1 ) . " ";
        print str_pad( "Extra", $extra_max_width+1 ) . PHP_EOL;

        $line_width = $field_max_width + $type_max_width + $null_max_width + $key_max_width + $default_max_width
                    + $extra_max_width + 15;
        print str_repeat("-", $line_width) . PHP_EOL;

        foreach( $colmn_attr_list as $colmn_attr ){
            $field = $colmn_attr['COLUMN_NAME'];
            $type = $colmn_attr['COLUMN_TYPE'];
            $null = $colmn_attr['IS_NULLABLE'];
            $key = $colmn_attr['COLUMN_KEY'];
            $default = $colmn_attr['COLUMN_DEFAULT'];
            $extra = $colmn_attr['EXTRA'];

            $field = str_pad( $field, $field_max_width+1 );
            $type = str_pad( $type, $type_max_width+1 );
            $null = str_pad( $null, $null_max_width+1 );
            $key = str_pad( $key, $key_max_width+1 );
            $default = str_pad( $default, $key_max_width+1 );
            $extra = str_pad( $extra, $extra_max_width+1 );

            print "$field $type $null $key $default $extra" . PHP_EOL;
        }

        print PHP_EOL . "Done." . PHP_EOL;

        return b(true);
    }

    /**
     * get max length of a field meta data
     *
     * @param array $field_meta
     * @param string $field
     *
     * @return boolean|Charcoal_Boolean
     */
    public function getMaxLengthOfMeta( $field_meta, $field )
    {
        $max_width = strlen($field);
        foreach( $field_meta as $colmn_attr ){
            $meta     = $colmn_attr[$field];
            $max_width = max( $max_width, strlen($meta) );
        }

        return $max_width;
    }
}

return __FILE__;
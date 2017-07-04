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

class CreateFromModelTask extends Charcoal_Task
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
        /** @var GenerateModelEvent $event */
        $event   = $context->getEvent();

        // get event parameters
        $db_name       = $event->getDatabase();

        /** @var Charcoal_SmartGateway $gw */
        $gw = $context->getComponent( 'smart_gateway@:charcoal:db' );

        // find models in project/app path
        $find_path = Charcoal_EnumFindPath::FIND_PATH_PROJECT | Charcoal_EnumFindPath::FIND_PATH_APPLICATION;
        $models = $gw->listModels( $find_path );

        // switch database
        $gw->selectDatabase( $db_name );

        // create tables
        foreach( $models as $model_name => $model ){

            $table = $model->getTableName();
            echo "creating table: [TABLE NAME]$table [MODEL NAME]$model_name\n";

            $rows_affected = $gw->createTable( null, $model_name, true );
            if ( $rows_affected ){
                echo "successfully created table[$table].\n";
            }
            else{
                echo "failed to create table[$table].\n";
            }
        }

        return b(true);
    }

}

return __FILE__;
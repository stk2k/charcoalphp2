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

class NewAppTask extends Charcoal_Task
{
    const DIR_MODE     = '666';

    /**
     * イベントを処理する
     */
    public function processEvent( $context )
    {
        $event = $context->getEvent();

        // パラメータを取得
        $app_name      = $event->getAppName();
        $project_name  = $event->getProjectName();
        $out_dir       = $event->getTargetDir();

        //=======================================
        // Confirm input parameters
        //=======================================
        if ( !preg_match('/^[0-9a-zA-Z_\-]*$/', $app_name) ){
            _throw( new Charcoal_InvalidArgumentException($app_name) );
        }
        if ( !preg_match('/^[0-9a-zA-Z_\-]*$/', $project_name) ){
            _throw( new Charcoal_InvalidArgumentException($project_name) );
        }

        //=======================================
        // Make output directory
        //=======================================
        $out_dir = new Charcoal_File( $out_dir );

        $out_dir->makeDirectory( self::DIR_MODE );

        //=======================================
        // Make web_app directory
        //=======================================

        $webapp_dir = new Charcoal_File( 'web_app', $out_dir );

        $webapp_dir->makeDirectory( self::DIR_MODE );

        //=======================================
        // Make project directory
        //=======================================

        $project_dir = new Charcoal_File( $project_name, $webapp_dir );

        $project_dir->makeDirectory( self::DIR_MODE );

        //=======================================
        // Make project/app directory
        //=======================================
        $project_app_dir = new Charcoal_File( 'app', $project_dir );

        $project_app_dir->makeDirectory( self::DIR_MODE );

        //=======================================
        // Make application directory
        //=======================================
        $application_dir = new Charcoal_File( $app_name, $project_app_dir );

        $application_dir->makeDirectory( self::DIR_MODE );

        //=======================================
        // Make application/class directory
        //=======================================
        $app_class_dir = new Charcoal_File( 'class', $application_dir );

        $app_class_dir->makeDirectory( self::DIR_MODE );

        //=======================================
        // Make application/config directory
        //=======================================
        $app_config_dir = new Charcoal_File( 'config', $application_dir );

        $app_config_dir->makeDirectory( self::DIR_MODE );

        //=======================================
        // Make application/module directory
        //=======================================
        $module_dir = new Charcoal_File( 'module', $application_dir );

        $module_dir->makeDirectory( self::DIR_MODE );

        echo "Application[$app_name] created at: " . $application_dir->getAbsolutePath() . PHP_EOL;

        return b(true);
    }
}

return __FILE__;
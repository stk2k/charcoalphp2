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

class NewProjectTask extends Charcoal_Task
{
	const DIR_MODE     = '666';

	/**
	 * イベントを処理する
	 */
	public function processEvent( $context )
	{
		$event = $context->getEvent();

		// パラメータを取得
		$project_name  = $event->getProjectName();
		$out_dir       = $event->getTargetDir();

		//=======================================
		// Confirm input parameters
		//=======================================
		if ( empty($project_name) || !preg_match('/^[0-9a-zA-Z_\-]*$/', $project_name) ){
			_throw( new Charcoal_InvalidArgumentException($project_name) );
		}

		//=======================================
		// Make output directory
		//=======================================
		$out_dir = new Charcoal_File( $out_dir );

		$out_dir->makeDirectory( self::DIR_MODE );

		//=======================================
		// Make public directory
		//=======================================
		$public_dir = new Charcoal_File( 'public_html', $out_dir );

		$public_dir->makeDirectory( self::DIR_MODE );

		//=======================================
		// Make ext_lib directory
		//=======================================
		$ext_lib_dir = new Charcoal_File( 'ext_lib', $out_dir );

		$ext_lib_dir->makeDirectory( self::DIR_MODE );

		//=======================================
		// Make logs directory
		//=======================================
		$logs_dir = new Charcoal_File( 'logs', $out_dir );

		$logs_dir->makeDirectory( self::DIR_MODE );

		//=======================================
		// Make sessions directory
		//=======================================
		$sessions_dir = new Charcoal_File( 'sessions', $out_dir );

		$sessions_dir->makeDirectory( self::DIR_MODE );

		//=======================================
		// Make cache directory
		//=======================================
		$cache_dir = new Charcoal_File( 'cache', $out_dir );

		$cache_dir->makeDirectory( self::DIR_MODE );

		//=======================================
		// Make tmp directory
		//=======================================
		$tmp_dir = new Charcoal_File( 'tmp', $out_dir );

		$tmp_dir->makeDirectory( self::DIR_MODE );

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
		// Make project/config directory
		//=======================================
		$project_config_dir = new Charcoal_File( 'config', $project_dir );

		$project_config_dir->makeDirectory( self::DIR_MODE );

		echo "Project[$project_name] created at: " . $project_dir->getAbsolutePath() . PHP_EOL;

		return b(true);
	}
}

return __FILE__;
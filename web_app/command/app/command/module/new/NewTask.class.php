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

class NewTask extends CommandTaskBase
{
	const DIR_MODE     = '666';

	/**
	 * イベントを処理する
	 */
	public function processEvent( $context )
	{
		$request   = $context->getRequest();
		$response  = $context->getResponse();
		$sequence  = $context->getSequence();
		$procedure = $context->getProcedure();

		$space_count = 30;

		// パラメータを取得
		$target        = $request->getString( 'p2' );
		$out_dir       = $request->getString( 'p3' );

		//=======================================
		// Split project / application
		//=======================================

		list($project_name,$app_name,$module_path,$task_name)  = explode('/', $target . '///');

		//=======================================
		// Confirm input parameters
		//=======================================
		if ( !empty($project_name) && !preg_match('/^[0-9a-zA-Z_\-]*$/', $project_name) ){
			_throw( new Charcoal_InvalidShellArgumentException($project_name,'p2') );
		}
		if ( !empty($module_path) && !preg_match('/^[@:0-9a-zA-Z_\-]*$/', $module_path) ){
			_throw( new Charcoal_InvalidShellArgumentException($module_path,'p2') );
		}
		if ( !empty($app_name) && !preg_match('/^[0-9a-zA-Z_\-]*$/', $app_name) ){
			_throw( new Charcoal_InvalidShellArgumentException($app_name,'p2') );
		}
		if ( !empty($task_name) && !preg_match('/^[@:0-9a-zA-Z_\-]*$/', $task_name) ){
			_throw( new Charcoal_InvalidShellArgumentException($task_name,'p2') );
		}
		if ( !empty($task_name) && (empty($module_path) || empty($app_name) || empty($project_name)) ){
			_throw( new Charcoal_InvalidShellArgumentException($target,'p2') );
		}
		if ( empty($task_name) && !empty($module_path) && (empty($app_name) || empty($project_name)) ){
			_throw( new Charcoal_InvalidShellArgumentException($target,'p2') );
		}
		if ( empty($task_name) && empty($module_path) && !empty($app_name) && empty($project_name) ){
			_throw( new Charcoal_InvalidShellArgumentException($target,'p2') );
		}

		//=======================================
		// output directory
		//=======================================

		if ( empty($out_dir) ){
			$out_dir = getcwd() ? getcwd() : Charcoal_ResourceLocator::getFrameworkPath( 'tmp' );
		}

		//=======================================
		// Send new project event
		//=======================================
		if ( !empty($project_name) ){
			$event_path = 'new_project_event@:charcoal:new:project';
			$event = $context->createEvent( $event_path, array($project_name, $out_dir) );
			$context->pushEvent( $event );
		}

		//=======================================
		// Send new application event
		//=======================================
		if ( !empty($app_name) ){
			$event_path = 'new_app_event@:charcoal:new:app';
			$event = $context->createEvent( $event_path, array($app_name, $project_name, $out_dir) );
			$context->pushEvent( $event );
		}

		//=======================================
		// Send new module event
		//=======================================
		if ( !empty($module_path) ){
			$event_path = 'new_module_event@:charcoal:new:module';
			$event = $context->createEvent( $event_path, array($module_path, $app_name, $project_name, $out_dir) );
			$context->pushEvent( $event );
		}

		//=======================================
		// Send new task event
		//=======================================
		if ( !empty($task_name) ){
			$event_path = 'new_task_event@:charcoal:new:task';
			$event = $context->createEvent( $event_path, array($task_name, $module_path, $app_name, $project_name, $out_dir) );
			$context->pushEvent( $event );
		}

		return b(true);
	}
}

return __FILE__;
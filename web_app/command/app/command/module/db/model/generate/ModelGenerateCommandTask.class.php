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

class ModelGenerateCommandTask extends CommandTaskBase
{
	const DIR_MODE     = '666';

	/**
	 * イベントを処理する
	 */
	public function processEvent( $context )
	{
		$request   = $context->getRequest();

		// パラメータを取得
		$table         = us( $request->getString( 'p2' ) );
		$out_dir       = us( $request->getString( 'p3' ) );

		//=======================================
		// Confirm input parameters
		//=======================================
		if ( !empty($table) && !preg_match('/^[0-9a-zA-Z_\-]*$/', $table) ){
			_throw( new Charcoal_InvalidShellArgumentException($table,'p2') );
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
		$event_path = 'model_generate_event@:charcoal:db:model:generate';
		$event = $context->createEvent( $event_path, array($table, $out_dir) );
		$context->pushEvent( $event );

		return b(true);
	}
}

return __FILE__;
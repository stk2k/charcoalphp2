<?php
/**
* Frontend interface of core hook
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_CoreHookList extends Charcoal_Object
{
	private $hooks;
	private $sandbox;

	/**
	 *  Constructor
	 */
	public function __construct( $sandbox )
	{
		Charcoal_ParamTrait::checkSandbox( 1, $sandbox );

		$this->sandbox = $sandbox;

		parent::__construct();
	}


	/**
	 * Register hook object
	 * 
	 * @param Charcoal_ICoreHook $hook        core hook to add
	 */
	public function add( Charcoal_ICoreHook $hook )
	{
		$this->hooks[] = $hook;
	}

	/**
	 * process core hook message
	 * 
	 * @param int $hook_stage      hook stage
	 * @param mixed $data          additional data
	 */
	public function processMessage( $hook_stage, $data = NULL )
	{
		if ( !$this->hooks )
		{
			$this->hooks = array();

			$hooks = $this->sandbox->getProfile()->getArray( 'CORE_HOOKS' );
			if ( $hooks ){
				foreach( $hooks as $hook_name ){
					if ( strlen($hook_name) === 0 )    continue;

					$hook = $this->sandbox->createObject( $hook_name, 'core_hook', array(), 'Charcoal_ICoreHook' );
					$this->hooks[] = $hook;
				}
			}
		}

		foreach( $this->hooks as $hook ){
			$hook->processMessage( $hook_stage, $data );
		}
	}
}


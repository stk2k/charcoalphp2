<?php
/**
* SmartGatewayのセッションハンドラ実装
*
* PHP version 5
*
* @package    session_handlers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_SmartGatewaySessionHandler extends Charcoal_CharcoalObject implements Charcoal_ISessionHandler
{
	private $gw;
	private $target;
	private $save_path;
	private $session_name;

	/**
	 *	constructor 
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		$this->target = $config->getString( s('target'), s('session') );

		$this->gw = Charcoal_DIContainer::getComponent( s('smart_gateway@:charcoal:db') );
	}

	/**
	 * open session
	 */
	public function open( $save_path, $session_name )
	{
		$this->save_path = $save_path;
		$this->session_name = $session_name;
		return true;
	}

	/**
	 * close session
	 */
	public function close()
	{
		return true;
	}

	/**
	 * read session data
	 */
	public function read( $id )
	{
		$criteria = new Charcoal_SQLCriteria( s('session_id = ?'), v(array($id)) );

		$dto = $this->gw->findFirst( qt($this->target), $criteria );

		return $dto ? $dto->session_data : NULL;
	}

	/**
	 * write session data
	 */
	public function write( $id, $sess_data )
	{
		$criteria = new Charcoal_SQLCriteria( s('session_id = ?'), v(array($id)) );

		$dto = $this->gw->findFirst( qt($this->target), $criteria );

		if ( !$dto ){
			$dto = new Charcoal_SessionTableDTO();
			$dto->session_id = $id;
			$dto->save_path = $this->save_path;
			$dto->session_name = $this->session_name;
		}
		$dto->session_data = $sess_data;

		try{
			$this->gw->beginTrans();

			$this->gw->save( s($this->target), $dto );

			$this->gw->commitTrans();
		}
		catch( Exception $ex )
		{
			_catch( $ex );

			$this->gw->rollbackTrans();

			_throw( new Charcoal_SessionHandlerException( 'write failed', $ex ) );
		}

		return true;
	}

	/**
	 * destroy session
	 */
	public function destroy( $id )
	{
		$this->gw->destroyById( s($this->target), i($id) );

		return true;
	}

	/**
	 * garbage collection
	 */
	public function gc( $max_lifetime )
	{
		$time_diff = date('h:i:s',strtotime($max_lifetime.'seconds'));

		$criteria = new Charcoal_SQLCriteria( s('modified < ADDTIME(NOW(),?)'), v(array($time_diff)) );

		$this->gw->destroyAll( s($this->target), $criteria );

		return true;
	}

}


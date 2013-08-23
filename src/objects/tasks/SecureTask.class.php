<?php
/**
* セキュアフルタスク
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

abstract class Charcoal_SecureTask extends Charcoal_Task implements Charcoal_ITask
{
	private $_is_secure;

	/*
	 *	コンストラクタ
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
	public function configure( Charcoal_Config $config )
	{
		parent::configure($config);

		$this->_is_secure     = $config->getBoolean( s('is_secure'), b(FALSE) );

	}

	/**
	 * ログインが必要なページか
	 */
	public function isSecure()
	{
		return $this->_is_secure;
	}

	/**
	 * ログインしているか
	 */
	public abstract function isAuthorized (Charcoal_ISequence $sequence );

	/**
	 * 権限を持っているか
	 */
	public abstract function hasPermission( Charcoal_IEventContext $context );

	/**
	 * ログインが必要なページでイベントを処理する
	 */
	public abstract function processEventSecure( Charcoal_IEventContext $context );

	/**
	 * パーミッションがない場合の処理をする
	 */
	public function permissionDenied( Charcoal_IEventContext $context )
	{
		return NULL;
	}

	/**
	 * Process events
	 *
	 * @param Charcoal_IEventContext $context   event context
	 */
	public function processEvent( Charcoal_IEventContext $context )
	{
		$sequence = $context->getSequence();
		$request  = $context->getRequest();

		// ログインチェック
		if ( $this->_is_secure->isTrue() && !$this->isAuthorized($sequence)->isTrue() )
		{
			// セキュリティ違反イベントを作成
			return Charcoal_Factory::createEvent( s('security_fault') );
		}

		// 権限チェック
		if ( $this->_is_secure->isTrue() && $this->isAuthorized($sequence)->isTrue() )
		{
			$has_permission = $this->hasPermission( $context );
			if ( $has_permission === NULL ){
				$has_permission = b(FALSE);
			}
			if ( !$has_permission->isTrue() )
			{
				// パーミッションがない場合の処理
				$event = $this->permissionDenied( $context );
				if ( $event ){
					return $event;
				}

				// パーミッション拒否イベントを生成
				return Charcoal_Factory::createEvent( s('permission_denied') );
			}
		}

		return $this->processEventSecure( $context );
	}
}

return __FILE__;
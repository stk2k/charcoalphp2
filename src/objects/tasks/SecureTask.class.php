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
	private $is_secure;

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
	public function configure( $config )
	{
		parent::configure($config);

		$this->is_secure     = $config->getBoolean( 'is_secure', FALSE );

	}

	/**
	 * ログインが必要なページか
	 */
	public function isSecure()
	{
		return $this->is_secure;
	}

	/**
	 * ログインしているか
	 */
	public abstract function isAuthorized( $sequence );

	/**
	 * 権限を持っているか
	 */
	public abstract function hasPermission( $context );

	/**
	 * ログインが必要なページでイベントを処理する
	 */
	public abstract function processEventSecure( $context );

	/**
	 * パーミッションがない場合の処理をする
	 */
	public function permissionDenied( $context )
	{
		return NULL;
	}

	/**
	 * Process events
	 *
	 * @param Charcoal_IEventContext $context   event context
	 */
	public function processEvent( $context )
	{
		$sequence = $context->getSequence();
		$request  = $context->getRequest();

		// ログインチェック
		if ( $this->is_secure === TRUE && !$this->isAuthorized( $sequence ) === TRUE )
		{
			// セキュリティ違反イベントを作成
			return Charcoal_Factory::createEvent( s('security_fault') );
		}

		// 権限チェック
		if ( $this->is_secure === TRUE && $this->isAuthorized( $sequence ) === TRUE )
		{
			$has_permission = $this->hasPermission( $context );
			if ( $has_permission === NULL ){
				$has_permission = FALSE;
			}
			if ( $has_permission !== TRUE )
			{
				// パーミッションがない場合の処理
				$event = $this->permissionDenied( $context );
				if ( $event ){
					return $event;
				}

				// パーミッション拒否イベントを生成
				return $this->getSandbox()->createEvent( 'permission_denied' );
			}
		}

		return $this->processEventSecure( $context );
	}
}


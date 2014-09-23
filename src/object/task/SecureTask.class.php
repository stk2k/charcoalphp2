<?php
/**
* セキュアフルタスク
*
* PHP version 5
*
* @package    objects.tasks
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
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

		$this->is_secure = ub( $config->getBoolean( 'is_secure', FALSE ) );

	}

	/**
	 * ログインが必要なページか
	 */
	public function isSecure()
	{
		return $this->is_secure;
	}

	/**
	 * check if the client is authorized.
	 * 
	 * @param Charcoal_EventContext $context      event ontext
	 */
	public abstract function isAuthorized( $context );

	/**
	 * check if the client has permission.
	 * 
	 * @param Charcoal_EventContext $context      event ontext
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
		if ( $this->is_secure )
		{
			// ログインチェック
			$auth = $this->isAuthorized( $context );
			if ( ub($auth) !== TRUE )
			{
				// セキュリティ違反イベントを作成
				return $this->getSandbox()->createEvent( s('security_fault') );
			}

			// 権限チェック
			$has_permission = $this->hasPermission( $context );
			if ( ub($has_permission) !== TRUE )
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


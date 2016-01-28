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
     *    コンストラクタ
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
     * @param Charcoal_IEventContext $context      event ontext
     */
    public abstract function isAuthorized( $context );

    /**
     * check if the client has permission.
     *
     * @param Charcoal_IEventContext $context      event ontext
     */
    public abstract function hasPermission( $context );

    /**
     * process event in secure task
     *
     * @param Charcoal_IEventContext $context      event ontext
     */
    public abstract function processEventSecure( $context );

    /**
     * process when access is denied
     *
     * @param Charcoal_IEventContext $context      event ontext
     *
     * @return mixed
     */
    public function permissionDenied( $context )
    {
        return NULL;
    }

    /**
     * Process events
     *
     * @param Charcoal_IEventContext $context   event context
     *
     * @return boolean|Charcoal_Boolean
     */
    public function processEvent( $context )
    {
        if ( $this->is_secure )
        {
            // check if the access is granted
            $auth = $this->isAuthorized( $context );
            if ( ub($auth) !== TRUE )
            {
                // create security fault event
                /** @var Charcoal_Event $event */
                $event = $this->getSandbox()->createEvent( 'security_fault' );
                $context->pushEvent($event);
                return b(TRUE);
            }

            // check permissions
            $has_permission = $this->hasPermission( $context );
            if ( ub($has_permission) !== TRUE )
            {
                $event = $this->permissionDenied( $context );
                if ( $event ){
                    return $event;
                }

                /** @var Charcoal_Event $event */
                $event = $this->getSandbox()->createEvent( 'permission_denied' );
                $context->pushEvent($event);
                return b(TRUE);
            }
        }

        return $this->processEventSecure( $context );
    }
}


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
    const TAG = 'charcoal_secure_task';
    
    /*
     *    コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * check if the client is authorized.
     *
     * @param Charcoal_IEventContext $context      event ontext
     */
    public abstract function isAuthorized( $context );
    
    /**
     * process when authorization failed
     *
     * @param Charcoal_IEventContext $context      event ontext
     *
     * @return mixed
     */
    public function authorizationFailed( $context )
    {
        return NULL;
    }

    /**
     * check if the client has permission.
     *
     * @param Charcoal_IEventContext $context      event ontext
     */
    public abstract function hasPermission( $context );
    
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
     * process event in secure task
     *
     * @param Charcoal_IEventContext $context      event ontext
     */
    public abstract function processEventSecure( $context );

    /**
     * Process events
     *
     * @param Charcoal_IEventContext $context   event context
     *
     * @return boolean|Charcoal_Boolean
     */
    public function processEvent( $context )
    {
        // check if the access is granted
        $auth = $this->isAuthorized( $context );
        log_debug('debug, security', 'isAuthorized result:' . $auth, self::TAG);
        if ( ub($auth) !== TRUE )
        {
            $event = $this->authorizationFailed( $context );
            log_debug('debug, security', 'authorizationFailed result:' . print_r($event,true), self::TAG);
            if ( $event ){
                return $event;
            }
    
            // throw security fault exception
            log_error('debug, security', 'throwing SecurityFaultException.', self::TAG);
            _throw( new Charcoal_SecurityFaultException() );
        }

        // check permissions
        $has_permission = $this->hasPermission( $context );
        log_debug('debug, security', 'hasPermission result:' . $has_permission, self::TAG);
        if ( ub($has_permission) !== TRUE )
        {
            $event = $this->permissionDenied( $context );
            log_debug('debug, security', 'permissionDenied result:' . print_r($event,true), self::TAG);
            if ( $event ){
                return $event;
            }
    
            // throw permission denied exception
            log_error('debug, security', 'throwing PermissionDeniedException.', self::TAG);
            _throw( new Charcoal_PermissionDeniedException() );
        }

        return $this->processEventSecure( $context );
    }
    
    /**
     * handle an exception
     *
     * @param Exception $e                        exception to handle
     * @param Charcoal_IEventContext $context      event context
     *
     * @return boolean|Charcoal_Boolean
     */
    public function handleException( $e, $context )
    {
        if ($e instanceof Charcoal_SecurityFaultException){
            // create security fault event
            /** @var Charcoal_Event $event */
            $event = $this->getSandbox()->createEvent( 'security_fault' );
            $context->pushEvent($event);
            return b(TRUE);
        }
        else if ($e instanceof Charcoal_PermissionDeniedException){
            // create permission denied event
            /** @var Charcoal_Event $event */
            $event = $this->getSandbox()->createEvent( 'permission_denied' );
            $context->pushEvent($event);
            return b(TRUE);
        }
        return b(false);
    }
}


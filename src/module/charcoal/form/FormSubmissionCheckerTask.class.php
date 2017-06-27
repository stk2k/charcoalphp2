<?php
/**
* Form Submission Checker Task
*
* checks duplicate form submission
*
* PHP version 5
*
* @package    modules.charcoal.form
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_FormSubmissionChekerTask extends Charcoal_Task implements Charcoal_ITask
{
    /**
     * Process events
     *
     * @param Charcoal_IEventContext $context   event context
     *
     * @return bool|Charcoal_Boolean
     */
    public function processEvent( $context )
    {
        $request   = $context->getRequest();
        $session  = $context->getSession();
        $event     = $context->getEvent();

        // form token component
        /** @var Charcoal_FormTokenComponent $form_token */
        $form_token = $context->getComponent( s('form_token@:charcoal:form') );

        if ( $event instanceof Charcoal_SetupEvent ){
            $form_token->generate( $session );
            return b(TRUE);
        }
        elseif ( $event instanceof Charcoal_AuthTokenEvent ){
            $form_token->validate( $session, $request );
            return b(TRUE);
        }

        return b(FALSE);
    }
}


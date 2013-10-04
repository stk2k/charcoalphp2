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
	/*
	 *	construct object
	 */
	public function __construct()
	{
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );
	}

	/**
	 * Process events
	 *
	 * @param Charcoal_IEventContext $context   event context
	 */
	public function processEvent( $context )
	{
		$request   = $context->getRequest();
		$response  = $context->getResponse();
		$sequence  = $context->getSequence();
		$procedure = $context->getProcedure();
		$event     = $context->getEvent();

		// form token component
		$form_token = $context->getComponent( s('form_token@:charcoal:form') );

		if ( $event instanceof Charcoal_SetupEvent ){
			$form_token->setupForm( $sequence, $response );
			return b(TRUE);
		}
		else if ( $event instanceof Charcoal_AuthTokenEvent ){
			return $form_token->checkToken( $sequence, $request );
		}

		return b(FALSE);
	}
}


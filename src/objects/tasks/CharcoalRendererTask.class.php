<?php
/**
* Charcoal renderer task
*
* PHP version 5
*
* @package    renderers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_CharcoalRendererTask extends Charcoal_Task implements Charcoal_ITask
{
	/**
	 *	Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/*
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration of component
	 */
	public function configure( Charcoal_Config $config )
	{
		parent::configure( $config );
	}

	/**
	 * Process events
	 *
	 * @param Charcoal_IEventContext $context   event context
	 */
	public function processEvent( Charcoal_IEventContext $context )
	{
		$event    = $context->getEvent();
		$response = $context->getResponse();
		$sequence = $context->getSequence();

		// output response headers
		$response->flushHeaders();

		// retrieve layout
		$layout = $event->getLayout();

//		log_info( "system,renderer", "Rendering by smarty. Layout:" . print_r($layout,true) );

		try{
			$charcoal = array();

			// page redirection
			if ( $layout instanceof Charcoal_IRedirectLayout ){
	
				$url = $layout->makeRedirectURL();

				$response->redirect( s($url) );

//				log_info( "system,renderer", "renderer", "Redirected to: $url" );
			}
			else if ( $event instanceof Charcoal_URLRedirectEvent ){
	
				$url = $event->getURL();

				$response->redirect( s($url) );

//				log_info( "system,renderer", "renderer", "Redirected to: $url" );
			}
			else{

				// Page information
				$page_info = $layout->getAttribute( s('page_info') );

				// Profile information
				$profile_config = Charcoal_Profile::getConfig();
				if ( $profile_config && is_array($profile_config) ){
					foreach( $profile_config as $key => $value ){
						$charcoal['profile'][$key] = $value;
					}
				}

				// Cookie information
				$cookies = $response->getCookies();
				if ( $cookies && is_array($cookies) ){
					foreach( $cookies as $key => $value ){
						$charcoal['cookie'][$key] = $value;
					}
				}

				// Assign variables
				if ( $page_info && is_array($page_info) ){
					foreach( $page_info as $key => $value ){
						$$key = $value;
					}
				}

				// Sequence data
				$charcoal['sequence'] = $sequence;

				// Request ID and reauest path
				$charcoal['request']['id']   = Charcoal_Framework::getRequestID();
				$charcoal['request']['path'] = Charcoal_Framework::getRequestPath();

				// Assign all
				//$charcoal = $charcoal;

				// Assign all response values
				$keys = $response->getKeys();
				foreach( $keys as $key ){
					$value = $response->get( s($key) );
					$smarty->assign( $key, $value );
				}

				// render template
				$template = $layout->getAttribute( s('layout') );

//				log_info( "smarty", "template=$template" );
				$html = $smarty->fetch( $template );
//				log_info( "smarty", "html=$html" );

				echo $html;
			}
		}
		catch ( Exception $ex )
		{
			_catch( $ex );

			_throw( new Charcoal_SmartyRendererTaskException( s("rendering failed"), $ex ) );
		}

		return b(TRUE);
	}
}


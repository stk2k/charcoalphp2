<?php
/**
* Smarty renderer task
*
* PHP version 5
*
* @package    objects.tasks
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
require_once( 'Smarty/Smarty.class.php' );

class Charcoal_SmartyRendererTask extends Charcoal_Task implements Charcoal_ITask
{
	private $template_files;
	private $smarty;

	/**
	 *	Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->template_files = array();
		$this->smarty = new Smarty();
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		$this->smarty->caching 				= 0;	//$config->getBoolean( 'caching' )->unbox();
		$this->smarty->compile_check 		= $config->getBoolean( 'compile_check', FALSE )->unbox();
		$this->smarty->template_dir 		= $config->getString( 'template_dir', '', TRUE )->unbox();
		$this->smarty->compile_dir 			= $config->getString( 'compile_dir', '', TRUE )->unbox();
		$this->smarty->config_dir 			= $config->getString( 'config_dir', '', TRUE )->unbox();
		$this->smarty->cache_dir 			= $config->getString( 'cache_dir', '', TRUE )->unbox();
		$this->smarty->left_delimiter 		= $config->getString( 'left_delimiter', '{', FALSE )->unbox();
		$this->smarty->right_delimiter 		= $config->getString( 'right_delimiter', '}', FALSE )->unbox();
//		$this->smarty->default_modifiers 	= $config->getArray( 'default_modifiers', array() )->unbox();

		$plugins_dir = $config->getArray( 'plugins_dir', array(), TRUE )->unbox();

		$this->smarty->plugins_dir	= empty($plugins_dir) ? 'plugins' : $plugins_dir;

		if ( $this->getSandbox()->isDebug() )
		{
			$smarty_options = array(
					'caching' => $this->smarty->caching,
					'compile_check' => $this->smarty->compile_check,
					'template_dir' => $this->smarty->template_dir,
					'compile_dir' => $this->smarty->compile_dir,
					'config_dir' => $this->smarty->config_dir,
					'cache_dir' => $this->smarty->cache_dir,
					'default_modifiers' => $this->smarty->default_modifiers,
					'plugins_dir' => $this->smarty->plugins_dir,
					'left_delimiter' => $this->smarty->left_delimiter,
					'right_delimiter' => $this->smarty->right_delimiter,
				);

			ad( $smarty_options );

			foreach( $smarty_options as $key => $value ){
				log_debug( 'system, debug, smarty', "smarty option: [$key]=" . Charcoal_System::toString($value) );
			}
		}
	}

	/**
	 * Process events
	 *
	 * @param Charcoal_IEventContext $context   event context
	 */
	public function processEvent( $context )
	{
		$event    = $context->getEvent();
		$response = $context->getResponse();
		$sequence = $context->getSequence();

		// output response headers
		$response->flushHeaders();

		// retrieve layout
		$layout = $event->getLayout();

//		log_info( "system,renderer", "renderer", "Rendering by smarty. Layout:" . print_r($layout,true) );

//		log_info( "smarty", "caching=" . $this->smarty->caching );
//		log_info( "smarty", "template_dir=" . $this->smarty->template_dir );
//		log_info( "smarty", "compile_dir=" . $this->smarty->compile_dir );
//		log_info( "smarty", "config_dir=" . $this->smarty->config_dir );
//		log_info( "smarty", "cache_dir=" . $this->smarty->cache_dir );

		$error_handler_old = NULL;

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
				log_info( "smarty","page_info=" . print_r($page_info,true) );

				// Profile information
				$profile_config = $this->getSandbox()->getProfile()->getAll();
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

				$smarty = $this->smarty;

				// Assign variables
				if ( $page_info && is_array($page_info) ){
					foreach( $page_info as $key => $value ){
						$smarty->assign( $key, $value );
					}
				}

				// Sequence data
				$charcoal['sequence'] = $sequence;

				// Request ID and reauest path
				$charcoal['request']['id']   = $this->getSandbox()->getEnvironment()->get( '%REQUEST_ID%' );
				$charcoal['request']['path'] = $this->getSandbox()->getEnvironment()->get( '%REQUEST_PATH%' );

				// Assign all
				$smarty->assign( 'charcoal', $charcoal );

				// Assign all response values
				$keys = $response->getKeys();
				foreach( $keys as $key ){
					$value = $response->get( s($key) );
					$smarty->assign( $key, $value );
				}

				$smarty->assign( '_smarty', $smarty );

				// render template
				$template = $layout->getAttribute( s('layout') );

				// set smarty error_reporting flags
				$this->smarty->error_reporting = E_ALL & ~E_STRICT & ~E_WARNING & ~E_NOTICE & ~(8192 /*= E_DEPRECATED */);

				// rewrite error handler
				$error_handler_old = set_error_handler( array($this,"onUnhandledError") );

				// compile and output template
				log_info( "smarty","template=$template" );
				$html = $smarty->fetch( $template );
				log_info( "smarty","html=$html" );

				echo $html;
			}
		}
		catch ( Exception $ex )
		{
			_catch( $ex );

			_throw( new Charcoal_SmartyRendererTaskException( "rendering failed", $ex ) );
		}

		if ( $error_handler_old ){
			set_error_handler( $error_handler_old );			
		}

		return b(TRUE);
	}

	/*
	 *	smarty error handler
	 */
	public static function onUnhandledError( $errno, $errstr, $errfile, $errline )
	{ 
		$flags_handled = error_reporting() ;
		if ( Charcoal_System::isBitSet( $errno, $flags_handled, Charcoal_System::BITTEST_MODE_ANY ) )
		{
			$errno_disp = Charcoal_System::phpErrorString( $errno );
			echo "smarty error [errno]$errno($errno_disp) [errstr]$errstr [errfile]$errfile [errline]$errline" . eol();
		}
		return TRUE;	// Otherwise, ignore all errors
	}

}


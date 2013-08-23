<?php
/**
* Smarty renderer task
*
* PHP version 5
*
* @package    renderers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
require_once( 'Smarty/Smarty.class.php' );

class Charcoal_SmartyRendererTask extends Charcoal_Task implements Charcoal_ITask
{
	var $_template_files;
	var $_smarty;

	/**
	 *	Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_template_files = array();
		$this->_smarty = new Smarty();
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config )
	{
		parent::configure( $config );

		$this->_smarty->caching 			= 0;	//$config->getBoolean( s('caching') )->getValue();
		$this->_smarty->compile_check 		= $config->getBoolean( s('compile_check'), b(FALSE) )->getValue();
		$this->_smarty->template_dir 		= $config->getString( s('template_dir'), s('') )->getValue();
		$this->_smarty->compile_dir 		= $config->getString( s('compile_dir'), s('') )->getValue();
		$this->_smarty->config_dir 			= $config->getString( s('config_dir'), s('') )->getValue();
		$this->_smarty->cache_dir 			= $config->getString( s('cache_dir'), s('') )->getValue();
		$this->_smarty->_default_modifiers 	= $config->getArray( s('default_modifiers'), v(array()) )->getValue();

		$plugins_dir = $config->getArray( s('plugins_dir'), v(array()) );
		if ( $plugins_dir->isEmpty() ){
			$this->_smarty->plugins_dir	= 'plugins';
		}
		else{
			$this->_smarty->plugins_dir	= $plugins_dir->getValue();
		}

		$left_delimiter = $config->getString( s('left_delimiter'), s('{') );
		if ( !$left_delimiter->isEmpty() ){
			$this->_smarty->left_delimiter 	= $left_delimiter->getValue();
		}
		$right_delimiter = $config->getString( s('right_delimiter'), s('}') );
		if ( !$right_delimiter->isEmpty() ){
			$this->_smarty->right_delimiter = $right_delimiter->getValue();
		}
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

//		log_info( "system,renderer", "renderer", "Rendering by smarty. Layout:" . print_r($layout,true) );

//		log_info( "smarty", "caching=" . $this->_smarty->caching );
//		log_info( "smarty", "template_dir=" . $this->_smarty->template_dir );
//		log_info( "smarty", "compile_dir=" . $this->_smarty->compile_dir );
//		log_info( "smarty", "config_dir=" . $this->_smarty->config_dir );
//		log_info( "smarty", "cache_dir=" . $this->_smarty->cache_dir );

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
				log_info( "smarty", "template", "page_info=" . print_r($page_info,true) );

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

				$smarty = $this->_smarty;

				// Assign variables
				if ( $page_info && is_array($page_info) ){
					foreach( $page_info as $key => $value ){
						$smarty->assign( $key, $value );
					}
				}

				// Sequence data
				$charcoal['sequence'] = $sequence;

				// Request ID and reauest path
				$charcoal['request']['id']   = Charcoal_Framework::getRequestID();
				$charcoal['request']['path'] = Charcoal_Framework::getRequestPath();

				// Assign all
				$smarty->assign( 'charcoal', $charcoal );

				// Assign all response values
				$keys = $response->getKeys();
				foreach( $keys as $key ){
					$value = $response->get( s($key) );
					$smarty->assign( $key, $value );
				}

				// render template
				$template = $layout->getAttribute( s('layout') );

				log_info( "smarty", "template", "template=$template" );
				$html = $smarty->fetch( $template );
				log_info( "smarty", "template", "html=$html" );

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

return __FILE__;
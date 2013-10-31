<?php
/**
* Form Token Component
*
* PHP version 5
*
* @package    component.charcoal.form
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
require_once( 'FormTokenComponentException.class.php' );
require_once( 'FormTokenValidationException.class.php' );

class Charcoal_FormTokenComponent extends Charcoal_CharcoalComponent implements Charcoal_IComponent
{
	private $_token_key;
	private $_debug_mode;
	private $_token_generator;

	/*
	 *	Construct object
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
		parent::configure( $config );

		log_debug( "debug", "config: " . print_r($config,true) );

		$this->_token_key        = $config->getString( s('token_key'), s('charcoal_token_key') )->getValue();
		$this->_debug_mode       = $config->getBoolean( 'debug_mode', FALSE )->getValue();
		$this->_token_generator  = $config->getString( s('token_generator'), s('simple') )->getValue();

		log_debug( "debug", "token key: {$this->_token_key}" );
		log_debug( "debug", "debug mode: {$this->_debug_mode}" );
		log_debug( "debug", "token generator: {$this->_token_generator}" );

		$this->_token_generator = $this->getSandbox()->createObject( $this->_token_generator, 'token_generator' );
	}

	/*
	 * Set token generator
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function setTokenGenerator( Charcoal_ITokenGenerator $token_generator )
	{
		$this->_token_generator = $token_generator;
	}

	/*
	 * Get token generator
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function getTokenGenerator()
	{
		return $this->_token_generator;
	}

	/*
	 * generate token
	 *
	 * @param Charcoal_ISequence $sequence    Request object
	 */
	public function generate( Charcoal_ISequence $sequence )
	{
		try{
			$token_key = $this->_token_key;

			// get token container from session.
			$token_list   = $sequence->get( s($token_key) );

			if ( $token_list === NULL || !is_array($token_list) ){
				$token_list = array();
			}

			// Generate token
			$new_token = $this->_token_generator->generateToken();

			if ( $this->_debug_mode ){
				ad($new_token,array('title'=>"token generated","type"=>"div"));
			}
			log_debug( "debug", "token generated: $new_token" );

			// add new token to token list.
			$token_list[] = $new_token;
			if ( $this->_debug_mode ){
				ad($token_list,array('title'=>"token list"));
			}

			// save token list in sequence.
			$sequence->set( s($token_key), $token_list );

			log_debug( "debug", "sequence: " . print_r($sequence,true) );

			return $new_token;
		}
		catch( Exception $e )
		{
			_catch( $e );

			_throw( new Charcoal_FormTokenComponentException( s(__CLASS__.'#'.__METHOD__.' failed.'), $e ) );
		}
	}

	/*
	 * validate token in request and sequence
	 *
	 * @param Charcoal_ISequence $sequence    Sequence object
	 * @param Charcoal_String $form_token    Form token
	 */
	public function validate( Charcoal_ISequence $sequence, Charcoal_String $form_token )
	{
		log_debug( "debug", "sequence: " . print_r($sequence,true) );
		log_debug( "debug", "form_token: " . print_r($form_token,true) );
		if ( $this->_debug_mode ){
			ad($sequence,array('title'=>"sequence"));
			ad($request,array('title'=>"request"));
		}

		$token_key = $this->_token_key;
		log_debug( "debug", "token_key: " . print_r($token_key,true) );
		if ( $this->_debug_mode ){
			ad($token_key,array('title'=>"token_key","type"=>"div"));
		}

		// get token container from session.
		$token_list   = $sequence->get( s($token_key) );
		if ( $this->_debug_mode ){
			ad($token_list,array('title'=>"token list"));
		}
		log_debug( "debug", "token_list: " . print_r($token_list,true) );

		if ( $token_list === NULL || !is_array($token_list) ){
			$token_list = array();
		}

		// find token from token list.
		$token_index = NULL;
		foreach( $token_list as $idx => $token ){
			log_info( "debug", "token: $token" );
			if ( $this->_debug_mode ){
				ad($token,array('title'=>"token","type"=>"div"));
			}
			if ( $token == $form_token ){
				$token_index = $idx;
				break;
			}
		}

		if ( $token_index === NULL ){
			// illegal access
			log_warning( "system, debug", "token not found: $form_token" );
			if ( $this->_debug_mode ){
				ad($form_token,array('title'=>"token not found","type"=>"div"));
			}

			_throw( new Charcoal_FormTokenValidationException( s('token not found in sequence:'.$form_token) ) );
		}
		else{
			// authorized access
			log_debug( "debug", "token accepted: $form_token" );
			if ( $this->_debug_mode ){
				ad($form_token,array('title'=>"token accepted","type"=>"div"));
			}

			// erase token from token list to prevent duplicate form submission.
			unset( $token_list[$token_index] );
		}

		// update token list in sequence.
		$sequence->set( s($token_key), $token_list );

		// the event was successfully processed.
		return b(TRUE);
	}

}


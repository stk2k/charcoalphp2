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
    private $token_key;
    private $debug_mode;

    /** @var  Charcoal_ITokenGenerator */
    private $token_generator;

    /*
     *    Construct object
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Initialize instance
     *
     * @param array $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );

        log_debug( "debug", "config: " . print_r($config,true) );
        
        $config = new Charcoal_HashMap($config);

        $this->token_key        = $config->getString( 'token_key', 'charcoaltoken_key' );
        $this->debug_mode       = $config->getBoolean( 'debug_mode', FALSE );
        $this->token_generator  = $config->getString( 'token_generator', 'simple' );

        log_debug( "debug", "token key: {$this->token_key}" );
        log_debug( "debug", "debug mode: {$this->debug_mode}" );
        log_debug( "debug", "token generator: {$this->token_generator}" );

        $this->token_generator = $this->getSandbox()->createObject( $this->token_generator, 'token_generator' );
    }

    /*
     * Set token generator
     *
     * @param Charcoal_ITokenGenerator $token_generator   token generator
     */
    public function setTokenGenerator( $token_generator )
    {
        Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_ITokenGenerator', $token_generator );

        $this->token_generator = $token_generator;
    }

    /*
     * Get token generator
     *
     * @return Charcoal_ITokenGenerator   token generator
     */
    public function getTokenGenerator()
    {
        return $this->token_generator;
    }

    /*
     * generate token
     *
     * @param Charcoal_Session $session
     *
     * @return string     new form token
     */
    public function generate( $session )
    {
        try{
            $token_key = $this->token_key;
            
            /** @var Charcoal_Session $session */

            // get token container from session.
            $token_list   = $session->getArray( $token_key );

            if ( $token_list === NULL || !is_array($token_list) ){
                $token_list = array();
            }

            // Generate token
            $new_token = $this->token_generator->generateToken();

            log_debug( "debug", "token generated: $new_token" );

            // add new token to token list.
            $token_list[] = $new_token;

            log_debug( "debug", "token_list: ", print_r($token_list, true) );

            // save token list in session.
            $session->set( $token_key, $token_list );

            log_debug( "debug", "session: " . print_r($session,true) );

            return $new_token;
        }
        catch( Exception $e )
        {
            _catch( $e );

            _throw( new Charcoal_FormTokenComponentException( s(__CLASS__.'#'.__METHOD__.' failed.'), $e ) );
        }
        return null;
    }

    /*
     * validate token in request and sequence
     *
     * @param Charcoal_Session $session             Session object
     * @param string|Charcoal_String $form_token    Form token
     * @param boolean|Charcoal_Boolean $throws      If true, this method throws an exception on failure. Otherwise returns true/false
     */
    public function validate( $session, $form_token, $throws = TRUE )
    {
        $throws = ub($throws);
    
        /** @var Charcoal_Session $session */

        log_debug( "debug", "session: " . print_r($session,true) );
        log_debug( "debug", "form_token: " . print_r($form_token,true) );

        if ( $this->getSandbox()->isDebug() && $this->debug_mode ){
            ad($session,array('title'=>"sequence"));
        }

        $token_key = $this->token_key;
        log_debug( "debug", "token_key: " . print_r($token_key,true) );
        if ( $this->getSandbox()->isDebug() && $this->debug_mode ){
            ad($token_key,array('title'=>"token_key","type"=>"div"));
        }

        // get token container from session.
        $token_list   = $session->getArray( $token_key );
        if ( $this->getSandbox()->isDebug() && $this->debug_mode ){
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
            if ( $this->getSandbox()->isDebug() && $this->debug_mode ){
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
            if ( $this->getSandbox()->isDebug() && $this->debug_mode ){
                ad($form_token,array('title'=>"token not found","type"=>"div"));
            }

            if ( $throws ){
                _throw( new Charcoal_FormTokenValidationException( 'token not found in sequence:'.$form_token ), FALSE );
            }
            return FALSE;
        }
        else{
            // authorized access
            log_debug( "debug", "token accepted: $form_token" );
            if ( $this->getSandbox()->isDebug() && $this->debug_mode ){
                ad($form_token,array('title'=>"token accepted","type"=>"div"));
            }

            // erase token from token list to prevent duplicate form submission.
            unset( $token_list[$token_index] );
        }

        // update token list in session.
        $session->set( $token_key, $token_list );

        // the event was successfully processed.
        return TRUE;
    }

}


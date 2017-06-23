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
/** @noinspection PhpIncludeInspection */
require_once( 'Smarty/Smarty.class.php' );

class Charcoal_SmartyRendererTask extends Charcoal_Task implements Charcoal_ITask
{
    const TAG = 'smarty_renderer_task';

    private $template_files;
    private $smarty;
    private $debug_mode;

    /**
     *    Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->template_files = array();
        $this->smarty = new Smarty();
        log_debug( "smarty", "smarty=" . spl_object_hash($this->smarty), self::TAG );
    }

    /**
     * Initialize instance
     *
     * @param Charcoal_Config $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );

        $this->debug_mode                    = ub( $config->getBoolean( 'debug_mode', FALSE ) );

        $this->smarty->caching                 = 0;    //$config->getBoolean( 'caching' )->unbox();
        $this->smarty->compile_check         = ub( $config->getBoolean( 'compile_check', FALSE ) );
        $this->smarty->template_dir         = us( $config->getString( 'template_dir', '', TRUE ) );
        $this->smarty->compile_dir             = us( $config->getString( 'compile_dir', '', TRUE ) );
        $this->smarty->config_dir             = us( $config->getString( 'config_dir', '', TRUE ) );
        $this->smarty->cache_dir             = us( $config->getString( 'cache_dir', '', TRUE ) );
        $this->smarty->left_delimiter         = us( $config->getString( 'left_delimiter', '{', FALSE ) );
        $this->smarty->right_delimiter         = us( $config->getString( 'right_delimiter', '}', FALSE ) );
//        $this->smarty->default_modifiers     = $config->getArray( 'default_modifiers', array() )->unbox();

        $plugins_dir = uv( $config->getArray( 'plugins_dir', array(), TRUE ) );

        // add default plugins_dir: Smarty/Smarty/plugins
        $reflector = new ReflectionClass($this->smarty);
        $plugins_dir[] = dirname($reflector->getFileName()) . '/plugins';

        $this->smarty->plugins_dir = $plugins_dir;

        log_debug( "smarty", "smarty->plugins_dir=" . print_r($this->smarty->plugins_dir, true), self::TAG );
        log_debug( "smarty", "smarty=" . spl_object_hash($this->smarty), self::TAG );

        if ( $this->debug_mode )
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
     *
     * @return boolean|Charcoal_Boolean
     */
    public function processEvent( $context )
    {
        log_debug( "smarty", "smarty=" . spl_object_hash($this->smarty), self::TAG );

        /** @var Charcoal_RenderLayoutEvent $event */
        $event    = $context->getEvent();
        /** @var Charcoal_HttpResponse $response */
        $response = $context->getResponse();
        $sequence = $context->getSequence();

        // output response headers
//        $response->flushHeaders();

        // retrieve layout
        $layout = $event->getLayout();

        log_debug( "smarty", "Rendering by smarty. Layout:" . print_r($layout,true), self::TAG );

        log_debug( "smarty", "caching=" . print_r($this->smarty->caching, true), self::TAG );
        log_debug( "smarty", "template_dir=" . print_r($this->smarty->template_dir, true), self::TAG );
        log_debug( "smarty", "compile_dir=" . print_r($this->smarty->compile_dir, true), self::TAG );
        log_debug( "smarty", "config_dir=" . print_r($this->smarty->config_dir, true), self::TAG );
        log_debug( "smarty", "cache_dir=" . print_r($this->smarty->cache_dir, true), self::TAG );
        log_debug( "smarty", "plugins_dir=" . print_r($this->smarty->plugins_dir, true), self::TAG );

        $error_handler_old = NULL;

        try{
            $charcoal = array();

            // page redirection
            if ( $layout instanceof Charcoal_IRedirectLayout ){

                $url = $layout->makeRedirectURL();

                $response->redirect( s($url) );

                log_debug( "system, debug, smarty, redirect", "redirected to URL: $url", self::TAG );
            }
            elseif ( $event instanceof Charcoal_URLRedirectEvent ){
                /** @var Charcoal_URLRedirectEvent $event */
                $url = $event->getURL();

                $response->redirect( s($url) );

                log_debug( "system, debug, smarty, redirect", "redirected to URL: $url", self::TAG );
            }
            elseif ( $event instanceof Charcoal_RenderLayoutEvent ){

                // Page information
                $page_info = $layout->getAttribute( s('page_info') );
                log_debug( "smarty", "page_info=" . print_r($page_info,true), self::TAG );

                // Profile information
                $profile_config = $this->getSandbox()->getProfile()->getAll();
                if ( $profile_config && is_array($profile_config) ){
                    foreach( $profile_config as $key => $value ){
                        $charcoal['profile'][$key] = $value;
                    }
                }

                // Cookie information
                if ( $response instanceof Charcoal_HttpResponse ){
                    $cookies = $response->getCookies();
                    if ( $cookies && is_array($cookies) ){
                        foreach( $cookies as $key => $value ){
                            $charcoal['cookie'][$key] = $value;
                        }
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

                // Assign all layout values
                $layout_values = $event->getValues();
                if ( !$layout_values ){
                    // If layout values are not set, response values will be used instead.
                    $layout_values = $response->getAll();
                }
                foreach( $layout_values as $key => $value ){
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
                log_debug( "smarty", "template=$template", self::TAG );
                $html = $smarty->fetch( $template );
                log_debug( "smarty", "html=$html", self::TAG );

                // output to rendering target
                $render_target = $event->getRenderTarget();
                if ( $render_target ){
                    $render_target->render( $html );
                    log_debug( "smarty", "Rendered by render target: $render_target", self::TAG );
                }
                else{
                    echo $html;
                    log_debug( "smarty", "Output by echo.", self::TAG );
                }
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
     *    smarty error handler
     */
    public static function onUnhandledError( $errno, $errstr, $errfile, $errline )
    {
        $flags_handled = error_reporting() ;
        if ( Charcoal_System::isBitSet( $errno, $flags_handled, Charcoal_System::BITTEST_MODE_ANY ) )
        {
            $errno_disp = Charcoal_System::phpErrorString( $errno );
            echo "smarty error [errno]$errno($errno_disp) [errstr]$errstr [errfile]$errfile [errline]$errline" . eol();
        }
        return TRUE;    // Otherwise, ignore all errors
    }

}


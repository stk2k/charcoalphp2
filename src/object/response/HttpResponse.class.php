<?php
/**
* HTTP Response
*
* PHP version 5
*
* @package    objects.responses
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_HttpResponse extends Charcoal_AbstractResponse
{
    const TAG = 'http_response';

    private $status;

    /** @var  Charcoal_CookieWriter $cookie */
    private $cookie;

    /** @var array Charcoal_HttpHeader[] $headers */
    private $headers;    // array of Charcoal_HttpHeader

    /**
     *    Construct object
     */
    public function __construct()
    {
        parent::__construct();

        $this->headers  = array();
    }

    /**
     * Initialize instance
     *
     * @param array $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );

        // use cookie
        $usecookie  = $this->getSandbox()->getProfile()->getBoolean( 'USE_COOKIE', FALSE );
        $this->cookie = $usecookie ? new Charcoal_CookieWriter() : NULL;
    }

    /**
     * destruct instance
     */
    public function terminate()
    {
        log_debug( 'system, debug, response', "terminating response.", self::TAG );

        $this->flushHeaders();
    }

    /**
     *  add response header
     *
     * @param Charcoal_String $header   header string to send
     * @param Charcoal_Boolean $replace   TRUE to replace same header
     *
     * @return Charcoal_String
     */
    public function addHeader( $header, $replace = TRUE )
    {
        $this->headers[] = new Charcoal_HttpHeader( $header, $replace );

        log_debug( 'system, debug, response', "header added : $header(replace=$replace)", self::TAG );
    }

    /**
     *  flush response header
     */
    public function flushHeaders()
    {
        // add cookie headers
        if ( $this->cookie ){
            //$this->cookie->writeAll();
        }

        // output headers
        foreach( $this->headers as $h ){
            /** @var Charcoal_HttpHeader $h */
            header( $h->getHeader(), $h->getReplace() );
            log_debug( 'system, debug, response', "header flushed: $h", self::TAG );
        }

        // erase all headers
        $this->headers = array();

        log_debug( 'system, debug, response', "headers are flushed.", self::TAG );
    }

    /**
     *  clear response header
     */
    public function clearHeaders()
    {
        if ( version_compare(PHP_VERSION, '5.3.0') >= 0 ){
            header_remove();
        }

        // erase all headers
        $this->headers = array();

        log_debug( 'system, debug, response', "headers are cleared.", self::TAG );
    }

    /**
     *  output HTTP header
     *
     * @param Charcoal_String|string $header            header to output
     * @param Charcoal_Boolean|boolean $flush_now        flushes header immediately
     */
    public function header( $header, $flush_now = TRUE )
    {
        $this->addHeader( $header );

        if ( $flush_now ){
            $this->flushHeaders();
        }
    }

    /**
     *  HTTP redirect(weak API)
     *
     * @param Charcoal_URL|string|Charcoal_String $url   Redirect URL
     * @param Charcoal_Boolean|boolean $flush_now   Flushes header immediately
     */
    public function redirect( $url, $flush_now = TRUE )
    {
//        $this->header( s("HTTP/1.0 302 Found"), $flush_now );
//        $this->clearHeaders();
        $this->header( "Location: $url", $flush_now );
        log_debug( 'system, debug, response', "Location: $url", self::TAG );
    }

    /**
     *  Get all cookie values as array
     *
     * @return array
     */
    public function getCookies()
    {
        return $this->cookie ? $this->cookie->getAll() : NULL;
    }

    /**
     *  Get cookie value
     *
     * @param Charcoal_String $name   cookie name to get
     *
     * @return Charcoal_String
     */
    public function getCookie( $name )
    {
        return $this->cookie ? $this->cookie->getValue( $name ) : NULL;
    }

    /**
     *  Set cookie value
     *
     * @param Charcoal_String $name   cookie name to set
     * @param Charcoal_String $value   cookie value to set
     *
     * @return Charcoal_String
     */
    public function setCookie( $name, $value )
    {
        if ( $this->cookie ){
            $this->cookie->setValue( $name, $value );
        }
    }

    /**
     *  Get status code
     *
     * @return int   HTTP status code
     */
    public function getStatusCode()
    {
        return $this->status;
    }

    /**
     *  Set HTTP status code
     *
     * @param Charcoal_Integer|integer $status_code   HTTP status code
     */
    public function setStatusCode( $status_code )
    {
        $this->status = ui($status_code);
    }


}


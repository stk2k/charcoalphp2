<?php
/**
* URLクラス
*
* PHP version 5
*
* @package    class.base
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_URL extends Charcoal_Object
{
    private $url;
    private $original;

    /**
     *    constructor
     */
    public function __construct( Charcoal_String $url )
    {
        parent::__construct();

        $this->original = $url;

        $this->url = parse_url($url);
        if ( $this->url === FALSE ){
            _throw( new Charcoal_URLFormatException( $url ) );
        }
    }

    /**
     *    get scheme part
     */
    public function getScheme()
    {
        return isset($this->url['scheme']) ? $this->url['scheme'] : NULL;
    }

    /**
     *    get host part
     */
    public function getHost()
    {
        return isset($this->url['host']) ? $this->url['host'] : NULL;
    }

    /**
     *    get port part
     */
    public function getPort()
    {
        return isset($this->url['port']) ? $this->url['port'] : NULL;
    }

    /**
     *    get user part
     */
    public function getUser()
    {
        return isset($this->url['user']) ? $this->url['puser'] : NULL;
    }

    /**
     *    get pass part
     */
    public function getPass()
    {
        return isset($this->url['pass']) ? $this->url['pass'] : NULL;
    }

    /**
     *    get path part
     */
    public function getPath()
    {
        return isset($this->url['path']) ? $this->url['path'] : NULL;
    }

    /**
     *    get query part
     */
    public function getQuery()
    {
        return isset($this->url['query']) ? $this->url['query'] : NULL;
    }

    /**
     *    get fragment part
     */
    public function getFragment()
    {
        return isset($this->url['fragment']) ? $this->url['fragment'] : NULL;
    }

    /**
     *    get some part
     */
    public function gett( Charcoal_String $key )
    {
        $key = us($key);
        return isset($this->url[$key]) ? $this->url[$key] : NULL;
    }

    /*
     *  String expression of this object
     *
     * @return string
     */
    public function toString()
    {
        return $this->original;
    }
}


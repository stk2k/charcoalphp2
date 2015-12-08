<?php
/**
* Cookie Component
*
* PHP version 5
*
* @package    component.charcoal.http
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_CookieComponent extends Charcoal_CharcoalComponent implements Charcoal_IComponent
{
    private $_impl;    // implementation class: Charcoal_Cookie

    /*
     *    Construct object
     */
    public function __construct()
    {
        parent::__construct();

        $this->_impl = new Charcoal_Cookie();
    }

    /**
     * Initialize instance
     *
     * @param Charcoal_Config $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );

        $this->_path      = $config->getString( s('path'), s('/') )->getValue();
        $this->_domain    = $config->getString( s('domain'), s('') )->getValue();
        $this->_secure    = $config->getBoolean( 'secure', FALSE )->getValue();
        $this->_httponly  = $config->getBoolean( 'httponly', FALSE )->getValue();
    }

    /*
     * Get cookie value keys
     */
    public function getKeys()
    {
        return $this->_impl->getKeys();
    }

    /*
     * Set cookie value
     */
    public function setValue( Charcoal_String $name, Charcoal_String $value )
    {
        $this->_impl->setValue( $name, $value );
    }

    /*
     * Get cookie value
     */
    public function getValue( Charcoal_String $name )
    {
        return $this->_impl->getValue( $name );
    }

    /*
     * Set cookie expire time
     */
    public function setExpire( Charcoal_Integer $expire )
    {
        $this->_impl->setExpire( $expire );
    }

    /*
     * Get cookie expire time
     */
    public function getExpire()
    {
        return $this->_impl->getExpire();
    }

    /*
     * Set cookie path
     */
    public function setPath( Charcoal_String $path )
    {
        $this->_impl->setPath( $path );
    }

    /*
     * Get cookie path
     */
    public function getPath()
    {
        return $this->_impl->getPath();
    }

    /*
     * Set cookie domain
     */
    public function setDomain( Charcoal_String $domain )
    {
        $this->_impl->setDomain( $domain );
    }

    /*
     * Get cookie domain
     */
    public function getDomain()
    {
        return $this->_impl->getDomain();
    }

    /*
     * Set cookie secure
     */
    public function setSecure( Charcoal_Boolean $secure )
    {
        $this->_impl->setSecure( $secure );
    }

    /*
     * Get cookie secure
     */
    public function isSecure()
    {
        return $this->_impl->isSecure();
    }

    /*
     * Set cookie http only
     */
    public function setHttpOnly( Charcoal_Boolean $httponly )
    {
        $this->_impl->setHttpOnly( $httponly );
    }

    /*
     * Get cookie secure
     */
    public function isHttpOnly()
    {
        return $this->_impl->isHttpOnly();
    }

    /*
     * Write cookie to client(auto URL encoded)
     */
    public function write()
    {
        return $this->_impl->write();
    }


    /*
     * Write cookie to client(no URL encoded)
     */
    public function writeRaw()
    {
        return $this->_impl->writeRaw();
    }
}


<?php
/**
* interface of sequence object
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_ISequence
{
    /*
     *  Get parameter
     *
     * @param string $key   Key string to get
     *
     * @return mixed   Returns NULL if key does not exist
     */
    public function get( $key );

    /**
     *  Get a global parameter
     *
     * @param string $key            Key string to get
     *
     * @return mixed
     */
    public function getGlobal( $key );

    /**
     *  Get a local parameter
     *
     * @param string $key            Key string to get
     *
     * @return mixed
     */
    public function getLocal( $key );

    /**
     *  set a parameter
     *
     * @param string $key            Key string to get
     * @param mixed $value           value to set
     */
    public function set( $key, $value );

    /**
     *  set a global parameter
     *
     * @param string $key            Key string to get
     * @param mixed $value           value to set
     */
    public function setGlobal( $key, $value );

    /**
     *  set a local parameter
     *
     * @param string $key            Key string to get
     * @param mixed $value           value to set
     */
    public function setLocal( $key, $value );



}


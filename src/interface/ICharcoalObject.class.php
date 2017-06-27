<?php
/**
* Interface of Charcoal framework object
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_ICharcoalObject extends Charcoal_IObject
{
    /**
     *   get ovject name
     *
     * @return Charcoal_String|string
     */
    public function getObjectName();

    /**
     *   get object path
     *
     * @return Charcoal_ObjectPath
     */
    public function getObjectPath();

    /**
     *   set object path
     *
     * @param Charcoal_ObjectPath $obj_path
     */
    public function setObjectPath( $obj_path );

    /**
     *   returns sandbox
     *
     * @return Charcoal_Sandbox           sandbox object
     */
    public function getSandbox();

    /**
     *   set sandbox
     *
     * @param Charcoal_Sandbox $sandbox          sandbox object
     */
    public function setSandbox( $sandbox );
}


<?php
/**
* framework basic object class
*
* PHP version 5
*
* @package    class.base
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_CharcoalObject extends Charcoal_Object
{
    /** @var string */
    private $obj_name;

    /** @var Charcoal_ObjectPath */
    private $obj_path;

    /** @var string */
    private $type_name;

    /** @var Charcoal_Sandbox */
    private $sandbox;

    /**
     *    constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->obj_name = Charcoal_System::snakeCase( get_class($this) );
    }

    /**
     *  get object name
     *
     * @return string           object name
     */
    public function getObjectName()
    {
        return $this->obj_name;
    }

    /**
     *  set object name
     *
     * @param Charcoal_String $obj_name          object name
     */
    public function setObjectName( $obj_name )
    {
//        Charcoal_ParamTrait::validateString( 1, $obj_name );

        $this->obj_name = $obj_name;
    }

    /**
     *   returns object path
     *
     * @return Charcoal_ObjectPath           object path
     */
    public function getObjectPath()
    {
        return $this->obj_path;
    }

    /**
     *   set object path
     *
     * @param Charcoal_String|Charcoal_ObjectPath $obj_path          object path
     */
    public function setObjectPath( $obj_path )
    {
//        Charcoal_ParamTrait::validateObjectPath( 1, $obj_path );

        $this->obj_path = $obj_path instanceof Charcoal_ObjectPath ? $obj_path : new Charcoal_ObjectPath($obj_path);
    }

    /**
     *   returns type name
     *
     * @return string           type name
     */
    public function getTypeName()
    {
        return $this->type_name;
    }

    /**
     *   set type name
     *
     * @param string $type_name          type name
     */
    public function setTypeName( $type_name )
    {
//        Charcoal_ParamTrait::validateString( 1, $type_name );

        $this->type_name = $type_name;
    }

    /**
     *   returns sandbox
     *
     * @return Charcoal_Sandbox           sandbox object
     */
    public function getSandbox()
    {
        return $this->sandbox;
    }

    /**
     *   set sandbox
     *
     * @param Charcoal_Sandbox $sandbox          sandbox object
     */
    public function setSandbox( $sandbox )
    {
//        Charcoal_ParamTrait::validateSandbox( 1, $sandbox );

        $this->sandbox = $sandbox;
    }

    /*
     *  String expression of this object
     *
     * @return string
     */
    public function toString()
    {
        $clazz = get_class($this);
        $hash = $this->hash();
        $path = $this->obj_path ? $this->obj_path : '(new)';
        $type = $this->type_name ? $this->type_name : '';

        return "[class=$clazz hash=$hash path=$path type=$type]";
    }
}

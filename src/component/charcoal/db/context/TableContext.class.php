<?php
/**
* Table context for fluent interface
*
* PHP version 5
*
* @package    component.charcoal.db.context
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_TableContext extends Charcoal_Object
{
    private $_gw;
    private $_model_name;

    /**
     *  Constructor
     */
    public function __construct( $gw, $model_name )
    {
        Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_SmartGateway', $gw );
        Charcoal_ParamTrait::validateString( 1, $model_name );

        $this->_gw = $gw;
        $this->_model_name = $model_name;
    }

    /**
     *  Get smart gateway
     */
    public function getSmartGateway()
    {
        return $this->_gw;
    }

    /**
     *  Get model name
     */
    public function getModelName()
    {
        return $this->_model_name;
    }

    /**
     *  Set model name
     */
    public function setModelName( $model_name )
    {
        $this->_model_name = $model_name;
    }

    /**
     *    [Fluent Interface] create fluent interface
     *
     * @param string $fields    field list(comma separated string) for SELECT clause
     *
     * @return Charcoal_SelectContext    select context
     */
    public function select( $fields )
    {
        Charcoal_ParamTrait::validateString( 1, $fields );

        $context = new Charcoal_QueryContext( $this->_gw );

        if ( !$fields->isEmpty() ){
            $fields = $fields->split( s(',') );
            $context->setFields( $fields );
        }

        return new Charcoal_SelectContext( $context );
    }

    /**
     *    Execute CREATE TABLE sql
     *
     *    @param string $model_name
     */
    public function create( $model_name, $if_not_exists = false )
    {
        return $this->_gw->createTable( $model_name, $if_not_exists );
    }

    /**
     *    Execute DROP TABLE sql
     *
     *    @param string $model_name
     */
    public function drop( $model_name, $if_exists = false )
    {
        return $this->_gw->createTable( $model_name, $if_exists );
    }
}


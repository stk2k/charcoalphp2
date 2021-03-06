<?php
/**
* Query context for fluent interface
*
* PHP version 5
*
* @package    component.charcoal.db.context
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_QueryContext extends Charcoal_Object
{
    private $_gw;
    private $_query_target;
    private $_criteria;
    private $_fields;
    private $_resultset;

    /**
     *  Constructor
     */
    public function __construct( $gw )
    {
        Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_SmartGateway', $gw );

        $this->_gw = $gw;
        $this->_criteria = new Charcoal_SQLCriteria();
    }

    /**
     *  Get smart gateway
     */
    public function getSmartGateway()
    {
        return $this->_gw;
    }

    /**
     *  Get query target
     */
    public function getQueryTarget()
    {
        return $this->_query_target;
    }

    /**
     *  Set query target
     */
    public function setQueryTarget( $query_target )
    {
        Charcoal_ParamTrait::validateIsA( 1, 'Charcoal_QueryTarget', $query_target );
        $this->_query_target = $query_target;
    }

    /**
     *  Get fields
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     *  Set fields
     */
    public function setFields( $fields )
    {
        Charcoal_ParamTrait::validateString( 1, $fields );
        $this->_fields = $fields;
    }

    /**
     *  Get criteria
     */
    public function getCriteria()
    {
        return $this->_criteria;
    }

    /**
     *  Set result set
     */
    public function setResultSet( $resultset )
    {
        $this->_resultset = $resultset;
    }

    /**
     *  Get criteria
     */
    public function getResultSet()
    {
        return $this->_resultset;
    }

    /**
     *  get count of records
     */
    public function count( $comment = '' )
    {
        return $this->_gw->count( $comment, $this->_query_target->toString(), $this->_criteria, $this->_fields );
    }

    /**
     *  get max of records
     */
    public function max( $comment = '' )
    {
        return $this->_gw->max( $comment, $this->_query_target, $this->_criteria, $this->_fields );
    }

    /**
     *  get min of records
     */
    public function min( $comment = '' )
    {
        return $this->_gw->min( $comment, $this->_query_target, $this->_criteria, $this->_fields );
    }

    /**
     *  get sum of records
     */
    public function sum( $comment = '' )
    {
        return $this->_gw->sum( $comment, $this->_query_target, $this->_criteria, $this->_fields );
    }

    /**
     *  get avg of records
     */
    public function avg( $comment = '' )
    {
        return $this->_gw->avg( $comment, $this->_query_target, $this->_criteria, $this->_fields );
    }

    /**
     *  find first record
     *
     * @return Charcoal_ResultContext    result context
     */
    public function findFirst( $comment = '' )
    {
        $this->_resultset = $this->_gw->findFirst( $comment, $this->_query_target, $this->_criteria, $this->_fields );

        return new Charcoal_ResultContext( $this );
    }

    /**
     *  find all records
     *
     * @return Charcoal_ResultContext    result context
     */
    public function findAll( $comment = '' )
    {
        $this->_resultset = $this->_gw->findAll( $comment, $this->_query_target, $this->_criteria, $this->_fields );

        return new Charcoal_ResultContext( $this );
    }

    /**
     *  find all records for update
     *
     * @return Charcoal_ResultContext    result context
     */
    public function findAllForUpdate( $comment = '' )
    {
        $this->_resultset = $this->_gw->findAllForUpdate( $comment, $this->_query_target, $this->_criteria, $this->_fields );

        return new Charcoal_ResultContext( $this );
    }


}


<?php
/**
* From context for fluent interface
*
* PHP version 5
*
* @package    component.charcoal.db.context
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_FromContext extends Charcoal_AbstractWrapperContext
{
    /**
     *  Constructor
     */
    public function __construct( $context )
    {
        parent::__construct( $context );
    }

    /**
     *  INNER/LEFT/RIGHT JOIN
     */
    public function join( $model_name, $alias = NULL, $join_type = NULL )
    {
        if ( $join_type === NULL ){
            $join_type = Charcoal_EnumSQLJoinType::INNER_JOIN;
        }

        $join = new Charcoal_QueryJoin( $join_type );
        $join->setModelName( $model_name );
        if ( $alias && !$alias->isEmpty() ){
            $join->setAlias( $alias );
        }
        $this->getContext()->getQueryTarget()->addJoin( $join );

        return new Charcoal_JoinContext( $this->getContext(), $join );
    }

    /**
     *  INNER JOIN
     */
    public function innerJoin( $model_name, $alias = NULL )
    {
        if ( $alias ){
            return $this->join( $model_name, $alias, Charcoal_EnumSQLJoinType::INNER_JOIN );
        }
        return $this->join( $model_name, '', Charcoal_EnumSQLJoinType::INNER_JOIN );
    }

    /**
     *  LEFT JOIN
     */
    public function leftJoin( $model_name, $alias = NULL )
    {
        if ( $alias ){
            return $this->join( $model_name, $alias, Charcoal_EnumSQLJoinType::LEFT_JOIN );
        }
        return $this->join( $model_name, '', Charcoal_EnumSQLJoinType::LEFT_JOIN );
    }

    /**
     *  RIGHT JOIN
     */
    public function rightJoin( $model_name, $alias = NULL )
    {
        if ( $alias ){
            return $this->join( $model_name, $alias, Charcoal_EnumSQLJoinType::RIGHT_JOIN );
        }
        return $this->join( $model_name, '', Charcoal_EnumSQLJoinType::RIGHT_JOIN );
    }


    /**
     *  indicates WHERE clause and switches to where context
     *
     * @return Charcoal_WhereContext    where context
     */
    public function where()
    {
        return new Charcoal_WhereContext( $this->getContext() );
    }

}


<?php
/**
* From context for fluent interface
*
* PHP version 5
*
* @package    components.charcoal.db.context
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
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
	public function join( Charcoal_String $model_name, Charcoal_String $alias = NULL, Charcoal_Integer $join_type = NULL )
	{
		if ( $join_type === NULL ){
			$join_type = Charcoal_EnumSQLJoinType::INNER_JOIN;
		}

		$join = new Charcoal_QueryJoin( i($join_type) );
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
	public function innerJoin( Charcoal_String $model_name, Charcoal_String $alias = NULL )
	{
		if ( $alias ){
			return $this->join( $model_name, $alias, i(Charcoal_EnumSQLJoinType::INNER_JOIN) );
		}
		return $this->join( $model_name, s(''), i(Charcoal_EnumSQLJoinType::INNER_JOIN) );
	}

	/**
	 *  LEFT JOIN
	 */
	public function leftJoin( Charcoal_String $model_name, Charcoal_String $alias = NULL )
	{
		if ( $alias ){
			return $this->join( $model_name, $alias, i(Charcoal_EnumSQLJoinType::LEFT_JOIN) );
		}
		return $this->join( $model_name, s(''), i(Charcoal_EnumSQLJoinType::LEFT_JOIN) );
	}

	/**
	 *  RIGHT JOIN
	 */
	public function rightJoin( Charcoal_String $model_name, Charcoal_String $alias = NULL )
	{
		if ( $alias ){
			return $this->join( $model_name, $alias, i(Charcoal_EnumSQLJoinType::RIGHT_JOIN) );
		}
		return $this->join( $model_name, s(''), i(Charcoal_EnumSQLJoinType::RIGHT_JOIN) );
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


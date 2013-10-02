<?php
/**
* Where context for fluent interface
*
* PHP version 5
*
* @package    component.charcoal.db.context
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_WhereContext extends Charcoal_AbstractWrapperContext
{
	/**
	 *  Constructor
	 */
	public function __construct( $context )
	{
		parent::__construct( $context );
	}

	/**
	 *  switch to prepared context 
	 *
	 * @return Charcoal_PreparedContext    prepared context
	 */
	public function prepare()
	{
		return new Charcoal_PreparedContext( $this->getContext() );
	}

	/**
	 *  switch to order by context 
	 *
	 * @return Charcoal_OrderByContext    order by context
	 */
	public function orderBy( Charcoal_String $order_by )
	{
		$this->getContext()->getCriteria()->setOrderBy( $order_by );

		return new Charcoal_OrderByContext( $this->getContext() );
	}

	/**
	 *  compare 
	 */
	public function compare( Charcoal_String $field, Charcoal_Primitive $value, Charcoal_String $operator )
	{
		$criteria = $this->getContext()->getCriteria();

		$where = "{$field} {$operator} ?";
		$params = array( $value->unbox() );

		$criteria->addWhere( s($where) );
		$criteria->addParams( v($params) );

		return $this;
	}

	/**
	 *  compare by equal(=) operator
	 */
	public function equal( Charcoal_String $field, Charcoal_Primitive $value )
	{
		return $this->compare( $field, $value, s('=') );
	}

	/**
	 *  compare by equal(<>) operator
	 */
	public function notEqual( Charcoal_String $field, Charcoal_Primitive $value )
	{
		return $this->compare( $field, $value, s('<>') );
	}

	/**
	 *  compare by equal(<>) operator
	 */
	public function ne( Charcoal_String $field, Charcoal_Primitive $value )
	{
		return $this->compare( $field, $value, s('<>') );
	}

	/**
	 *  compare by greater than(>) operator
	 */
	public function greaterThan( Charcoal_String $field, Charcoal_Primitive $value )
	{
		return $this->compare( $field, $value, s('>') );
	}

	/**
	 *  compare by greater than(>) operator
	 */
	public function gt( Charcoal_String $field, Charcoal_Primitive $value )
	{
		return $this->compare( $field, $value, s('>') );
	}

	/**
	 *  compare by greater than or equal(>=) operator
	 */
	public function greaterThanOrEqual( Charcoal_String $field, Charcoal_Primitive $value )
	{
		return $this->compare( $field, $value, s('>=') );
	}

	/**
	 *  compare by greater than or equal(>=) operator
	 */
	public function gte( Charcoal_String $field, Charcoal_Primitive $value )
	{
		return $this->compare( $field, $value, s('>=') );
	}

	/**
	 *  compare by less than(<) operator
	 */
	public function lessThan( Charcoal_String $field, Charcoal_Primitive $value )
	{
		return $this->compare( $field, $value, s('<') );
	}

	/**
	 *  compare by less than(<) operator
	 */
	public function lt( Charcoal_String $field, Charcoal_Primitive $value )
	{
		return $this->compare( $field, $value, s('<') );
	}

	/**
	 *  compare by less than or equal(<=) operator
	 */
	public function lessThanOrEqual( Charcoal_String $field, Charcoal_Primitive $value )
	{
		return $this->compare( $field, $value, s('<') );
	}

	/**
	 *  compare by less than or equal(<=) operator
	 */
	public function lte( Charcoal_String $field, Charcoal_Primitive $value )
	{
		return $this->compare( $field, $value, s('<') );
	}

	/**
	 *  LIKE
	 */
	public function like( Charcoal_String $field, Charcoal_Primitive $value )
	{
		return $this->compare( $field, $value, s('LIKE') );
	}

	/**
	 *  BETWEEN
	 */
	public function between( Charcoal_String $field, Charcoal_Primitive $value1, Charcoal_Primitive $value2 )
	{
		$criteria = $this->getContext()->getCriteria();

		$where = "{$field} BETWEEN ? AND ?";
		$params = array( $value1->unbox(), $value2->unbox() );

		$criteria->addWhere( s($where) );
		$criteria->addParams( v($params) );

		return $this;
	}

	/**
	 *  NOT BETWEEN
	 */
	public function notBetween( Charcoal_String $field, Charcoal_Primitive $value1, Charcoal_Primitive $value2 )
	{
		$criteria = $this->getContext()->getCriteria();

		$where = "{$field} NOT BETWEEN ? AND ?";
		$params = array( $value1->unbox(), $value2->unbox() );

		$criteria->addWhere( s($where) );
		$criteria->addParams( v($params) );

		return $this;
	}

	/**
	 *  IS
	 */
	public function is( Charcoal_String $field, Charcoal_Primitive $value )
	{
		return $this->compare( $field, $value, s('IS') );
	}

	/**
	 *  IS NOT
	 */
	public function isNot( Charcoal_String $field, Charcoal_Primitive $value )
	{
		return $this->compare( $field, $value, s('IS NOT') );
	}

	/**
	 *  IS NULL
	 */
	public function isNull( Charcoal_String $field )
	{
		$criteria = $this->getContext()->getCriteria();

		$where = "{$field} IS NULL";

		$criteria->addWhere( s($where) );

		return $this;
	}

	/**
	 *  IS NOT NULL
	 */
	public function isNotNull( Charcoal_String $field )
	{
		$criteria = $this->getContext()->getCriteria();

		$where = "{$field} IS NOT NULL";

		$criteria->addWhere( s($where) );

		return $this;
	}

	/**
	 *  IN
	 */
	public function in( Charcoal_String $field, Charcoal_Vector $values )
	{
		$criteria = $this->getContext()->getCriteria();

		$where = "{$field} IN (";
		$params = array();
		foreach( $values as $val ){
			if ( count($params) > 0 ){
				$where .= ',';
			}
			$where .= '?';
			$params[] = $val->unbox();
		}
		$where .= ')';

		$criteria->addWhere( s($where) );
		$criteria->addParams( v($params) );

		return $this;
	}

	/**
	 *  NOT IN
	 */
	public function notIn( Charcoal_String $field, Charcoal_Vector $values )
	{
		$criteria = $this->getContext()->getCriteria();

		$where = "{$field} NOT IN (";
		$params = array();
		foreach( $values as $val ){
			if ( count($params) > 0 ){
				$where .= ',';
			}
			$where .= '?';
			$params[] = $val->unbox();
		}
		$where .= ')';

		$criteria->addWhere( s($where) );
		$criteria->addParams( v($params) );

		return $this;
	}

}


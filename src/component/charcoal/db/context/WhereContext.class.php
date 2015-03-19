<?php
/**
* Where context for fluent interface
*
* PHP version 5
*
* @package    component.charcoal.db.context
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
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
	public function prepareExecute()
	{
		return new Charcoal_PreparedContext( $this->getContext() );
	}

	/**
	 *  switch to order by context 
	 *
	 * @return Charcoal_OrderByContext    order by context
	 */
	public function orderBy( $order_by )
	{
		$this->getContext()->getCriteria()->setOrderBy( $order_by );

		return new Charcoal_OrderByContext( $this->getContext() );
	}

	/**
	 *  compare 
	 */
	public function compare( $field, $value, $operator )
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
	public function equal( $field, $value )
	{
		return $this->compare( $field, $value, s('=') );
	}

	/**
	 *  compare by equal(<>) operator
	 */
	public function notEqual( $field, $value )
	{
		return $this->compare( $field, $value, s('<>') );
	}

	/**
	 *  compare by equal(<>) operator
	 */
	public function ne( $field, $value )
	{
		return $this->compare( $field, $value, s('<>') );
	}

	/**
	 *  compare by greater than(>) operator
	 */
	public function greaterThan( $field, $value )
	{
		return $this->compare( $field, $value, s('>') );
	}

	/**
	 *  compare by greater than(>) operator
	 */
	public function gt( $field, $value )
	{
		return $this->compare( $field, $value, s('>') );
	}

	/**
	 *  compare by greater than or equal(>=) operator
	 */
	public function greaterThanOrEqual( $field, $value )
	{
		return $this->compare( $field, $value, s('>=') );
	}

	/**
	 *  compare by greater than or equal(>=) operator
	 */
	public function gte( $field, $value )
	{
		return $this->compare( $field, $value, s('>=') );
	}

	/**
	 *  compare by less than(<) operator
	 */
	public function lessThan( $field, $value )
	{
		return $this->compare( $field, $value, s('<') );
	}

	/**
	 *  compare by less than(<) operator
	 */
	public function lt( $field, $value )
	{
		return $this->compare( $field, $value, s('<') );
	}

	/**
	 *  compare by less than or equal(<=) operator
	 */
	public function lessThanOrEqual( $field, $value )
	{
		return $this->compare( $field, $value, s('<') );
	}

	/**
	 *  compare by less than or equal(<=) operator
	 */
	public function lte( $field, $value )
	{
		return $this->compare( $field, $value, s('<') );
	}

	/**
	 *  LIKE
	 */
	public function like( $field, $value )
	{
		return $this->compare( $field, $value, s('LIKE') );
	}

	/**
	 *  BETWEEN
	 */
	public function between( $field, $value1, $value2 )
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
	public function notBetween( $field, $value1, $value2 )
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
	public function is( $field, $value )
	{
		return $this->compare( $field, $value, s('IS') );
	}

	/**
	 *  IS NOT
	 */
	public function isNot( $field, $value )
	{
		return $this->compare( $field, $value, s('IS NOT') );
	}

	/**
	 *  IS NULL
	 */
	public function isNull( $field )
	{
		$criteria = $this->getContext()->getCriteria();

		$where = "{$field} IS NULL";

		$criteria->addWhere( s($where) );

		return $this;
	}

	/**
	 *  IS NOT NULL
	 */
	public function isNotNull( $field )
	{
		$criteria = $this->getContext()->getCriteria();

		$where = "{$field} IS NOT NULL";

		$criteria->addWhere( s($where) );

		return $this;
	}

	/**
	 *  IN
	 */
	public function in( $field, Charcoal_Vector $values )
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
	public function notIn( $field, Charcoal_Vector $values )
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


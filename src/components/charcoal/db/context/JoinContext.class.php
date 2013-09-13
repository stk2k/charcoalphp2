<?php
/**
* From context for fluent interface
*
* PHP version 5
*
* @package    components.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_JoinContext extends Charcoal_AbstractWrapperContext
{
	private $_join;

	/**
	 *  Constructor
	 */
	public function __construct( $context, $join )
	{
		parent::__construct( $context );

		$this->_join = $join;
	}

	/**
	 *  INNER/LEFT/RIGHT JOIN
	 */
	public function on( Charcoal_String $condition )
	{
		$this->_join->setCondition( $condition );

		return $this;
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


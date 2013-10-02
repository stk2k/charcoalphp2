<?php
/**
* Select context for fluent interface
*
* PHP version 5
*
* @package    component.charcoal.db.context
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_SelectContext extends Charcoal_AbstractWrapperContext
{
	/**
	 *  Constructor
	 */
	public function __construct( $context )
	{
		parent::__construct( $context );
	}

	/**
	 *  indicates FROM clause and switches to from context
	 *
	 * @param Charcoal_String $model_name    model name for table
	 * @param Charcoal_String $alias         alias name for table
	 *
	 * @return Charcoal_FromContext    from context
	 */
	public function from( $model_name, $alias = NULL )
	{
		Charcoal_ParamTrait::checkString( 1, $model_name );
		Charcoal_ParamTrait::checkString( 2, $alias, TRUE );

		$query_target = new Charcoal_QueryTarget( $model_name );

		if ( $alias ){
			$query_target->setAlias( $alias );
		}

		$this->getContext()->setQueryTarget( $query_target );

		return new Charcoal_FromContext( $this->getContext() );
	}

}


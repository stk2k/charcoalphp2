<?php
/**
* Select context for fluent interface
*
* PHP version 5
*
* @package    components.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_SelectContext extends Charcoal_Object
{
	private $_context;

	/**
	 *  Constructor
	 */
	public function __construct( Charcoal_QueryContext $context )
	{
		$this->_context = $context;
	}

	/**
	 *  indicates FROM clause and switches to from context
	 *
	 * @param Charcoal_String $model_name    model name for table
	 * @param Charcoal_String $alias         alias name for table
	 *
	 * @return Charcoal_FromContext    from context
	 */
	public function from( Charcoal_String $model_name, Charcoal_String $alias = NULL )
	{
		$target = us($model_name) . ($alias ? " AS {$alias}" : s(''));

		$query_target = new Charcoal_QueryTarget( s($target) );

		$this->_context->setQueryTarget( $query_target );

		return new Charcoal_FromContext( $this->_context );
	}

}
return __FILE__;

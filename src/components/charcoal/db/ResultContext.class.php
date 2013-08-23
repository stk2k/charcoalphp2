<?php
/**
* Result context for fluent interface
*
* PHP version 5
*
* @package    components.db
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_ResultContext extends Charcoal_Object
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
	 *  return current record set
	 */
	public function result()
	{
		return $this->_context->getResultSet();
	}

	/**
	 *  Make list of copies on target DTO
	 */
	public function pack( Charcoal_DTO $target_dto )
	{
		$rs = $this->_context->getResultSet();

		if ( $rs && is_array($rs) ){
			$collected_rs = array();
			foreach( $rs as $record_dto ){
				$to_dto = clone $target_dto;

				$values = $record_dto->getAll();
				foreach( $values as $key => $val ){
					if ( property_exists($to_dto,$key) ){
						$to_dto->$key = $val;
					}
				}

				$collected_rs[] = $to_dto;
			}
			$this->_context->setResultSet( v($collected_rs) );
		}
		return $this;
	}

	/**
	 *  transform
	 */
	public function transform( Charcoal_ITransformer $transformer, Charcoal_DTO $target_dto, Charcoal_Properties $options = NULL )
	{
		$rs = $this->_context->getResultSet();

		if ( $rs && is_array($rs) ){
			$transformed_rs = array();
			foreach( $rs as $record_dto ){
				$to_dto = clone $target_dto;

				if ( $options )
					$transformer->transform( $record_dto, $to_dto, $options );
				else
					$transformer->transform( $record_dto, $to_dto );

				$transformed_rs[] = $to_dto;
			}
			$this->_context->setResultSet( v($transformed_rs) );
		}

		return $this;
	}


}
return __FILE__;

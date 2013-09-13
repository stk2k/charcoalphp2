<?php
/**
* Response base class
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_AbstractResponse extends Charcoal_CharcoalObject implements Charcoal_IResponse
{
	private $_vars;
	private $_filters;

	/*
	 *	Construct object
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/*
	 *  Get all keys included in this container
	 *
	 * @return array
	 */
	public function getKeys() 
	{
		return array_keys($this->_vars);
	}

	/**
	 *	Get all values with keys
	 *
	 * @return array
	 */
	public function getAll()
	{
		return $this->_vars;
	}

	/*
	 *  Get value from container if specified key is included, otherwise returns NULL.
	 *
	 * @param Charcoal_String $key   Key of the value 
	 *
	 * @return mixed
	 */
	public function get( Charcoal_String $key )
	{
		$key = us($key);
		return isset($this->_vars[$key]) ? $this->_vars[$key] : NULL;
	}

	/*
	 *  Set value in container.
	 *
	 * @param Charcoal_String $key   Key of the value 
	 * @param mixed $value   Value to set
	 * @param Charcoal_Boolean $skip_filters   If TRUE, skip all registered filters
	 *
	 * @return mixed
	 */
	public function set( Charcoal_String $key, $value, Charcoal_Boolean $skip_filters = NULL )
	{
		if ( $skip_filters === NULL ){
			$skip_filters = b(FALSE);
		}
		if ( !$skip_filters->isTrue() ){
			$value = $this->_applyAllFilters($value);
		}
		$this->_vars[us($key)] = $value;
	}

	/**
	 *	Set all array elements
	 *	
	 *	@param array $array   array data to set
	 */
	public function setArray( array $data )
	{
		$this->_vars = array_merge( $this->_vars, $data );
	}

	/*
	 *	Set all elements in a Properties object into container. All of container elements will be overwrited.
	 *
	 * @param Charcoal_Properties $data   Properties object to set
	 */
	public function setProperties( Charcoal_Properties $data )
	{
		$this->_vars = array_merge( $this->_vars, $data->getAll() );
	}

	/*
	 *	Merge all elements in a Properties object into container
	 *
	 * @param Charcoal_Properties $data   Properties object to merge
	 * @param Charcoal_Boolean $overwrite   If TRUE, container values will be overwrited by properties data.
	 */
	public function mergeProperties( Charcoal_Properties $data, Charcoal_Boolean $overwrite = NULL )
	{
		$overwrite = $overwrite ? $overwrite->isTrue() : FALSE;

		foreach( $data as $key => $value ){
			if ( !isset($this->_vars[$key]) || $overwrite ){
				$this->_vars[$key] = $value;
			}
		}
	}

	/*
	 * Copy parameters from request object. All of container elements will be overwrited.
	 *
	 * @param Charcoal_IRequest $data   Request object to set
	 */
	public function setFromRequest( Charcoal_IRequest $data )
	{
		$this->_vars = array_merge( $this->_vars, $request->getAll() );
	}

	/*
	 *  apply all filters
	 *
	 * @param mixed $value   Value to apply
	 */
	private function _applyAllFilters( $value )
	{
		if ( !$this->_filters ){
			$this->_filters = array();

			$response_filters = $this->getSandbox()->getProfile()->getArray( 'RESPONSE_FILTERS', array() );
			if ( $response_filters ){
				foreach( $response_filters as $filter_name ){
					if ( strlen($filter_name) === 0 )    continue;

					$filter = $this->getSandbox()->createObject( $filter_name, 'response_filter', array(), 'Charcoal_IResponseFilter' );
					$this->_filters[] = $filter;
				}
			}
		}

		// apply all filters
		foreach( $this->_filters as $filter ){
			$value = $filter->apply( $value );
		}

		return $value;
	}

	/*
	 * Add response filter
	 *
	 * @param Charcoal_IResponseFilter $filter filter to add
	 */
	public function addResponseFilter( Charcoal_IResponseFilter $filter )
	{
		$this->_filters[] = $filter;
	}

	/*
	 * Remove response filter
	 *
	 * @param Charcoal_IResponseFilter $filter filter to remove
	 */
	public function removeResponseFilter( Charcoal_IResponseFilter $filter )
	{
		if ( $this->_filters && is_array($this->_filters) ){
			foreach( $this->_filters as $key => $f ){
				if ( $f->equals($filter) ){
					unnset( $this->_filters[$key] );
					return;
				}
			}
		}
	}
}


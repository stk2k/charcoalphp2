<?php
/**
* Response Interface
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_IResponse extends Charcoal_ICharcoalObject, Iterator, ArrayAccess
{
	/**
	 * Add response filter
	 *
	 * @param Charcoal_IResponseFilter $filter filter to add
	 */
	public function addResponseFilter( $filter );

	/**
	 * Remove response filter
	 *
	 * @param Charcoal_IResponseFilter $filter filter to remove
	 */
	public function removeResponseFilter( $filter );

	/**
	 *  Get all keys included in this container
	 *
	 * @return array
	 */
	public function getKeys();

	/**
	 *  Get value from container if specified key is included, otherwise returns NULL.
	 *
	 * @param string $key   Key of the value 
	 *
	 * @return mixed
	 */
	public function get( $key );

	/**
	 *	Get all values with keys
	 *
	 * @return array
	 */
	public function getAll();

	/**
	 *  Set value in container.
	 *
	 * @param string $key             Key of the value 
	 * @param mixed $value            Value to set
	 * @param boolean $skip_filters   If TRUE, ignore all registered filters
	 */
	public function set( $key, $value, $skip_filters = FALSE );

	/**
	 *	Set all array elements
	 *	
	 *	@param array $array   array data to set
	 */
	public function setArray( $array );

	/**
	 *	Merge all elements in an array into container
	 *
	 * @param Charcoal_HashMap $map   HashMap object to merge
	 * @param boolean $overwrite      If TRUE, container values will be overwrited by properties data.
	 */
	public function mergeArray( $array, $overwrite = TRUE );

	/**
	 *	Set all elements in a HashMap object into container. All of container elements will be overwrited.
	 *
	 * @param Charcoal_HashMap $map   HashMap object to set
	 */
	public function setHashMap( $map );

	/**
	 *	Merge all elements in a HashMap object into container
	 *
	 * @param Charcoal_HashMap $map   HashMap object to merge
	 * @param boolean $overwrite      If TRUE, container values will be overwrited by properties data.
	 */
	public function mergeHashMap( $map, $overwrite = TRUE );

	/**
	 * Copy parameters from request object. All of container elements will be overwrited.
	 *
	 * @param Charcoal_IRequest $request   Request object to set
	 */
	public function setRequest( $request );

	/**
	 *	Merge all elements in a HashMap object into container
	 *
	 * @param Charcoal_IRequest $request   HashMap object to merge
	 * @param boolean $overwrite      If TRUE, container values will be overwrited by properties data.
	 */
	public function mergeRequest( $request, $overwrite = TRUE );

}


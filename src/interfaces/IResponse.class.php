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
interface Charcoal_IResponse extends Charcoal_ICharcoalObject
{
	/*
	 * Add response filter
	 *
	 * @param Charcoal_IResponseFilter $filter filter to add
	 */
	public function addResponseFilter( Charcoal_IResponseFilter $filter );

	/*
	 * Remove response filter
	 *
	 * @param Charcoal_IResponseFilter $filter filter to remove
	 */
	public function removeResponseFilter( Charcoal_IResponseFilter $filter );

	/*
	 *  Get all keys included in this container
	 *
	 * @return array
	 */
	public function getKeys();

	/*
	 *  Get value from container if specified key is included, otherwise returns NULL.
	 *
	 * @param Charcoal_String $key   Key of the value 
	 *
	 * @return mixed
	 */
	public function get( Charcoal_String $key );

	/*
	 *  Get all values from container.
	 *
	 * @return array
	 */
	public function getAll();

	/*
	 *  Set value in container.
	 *
	 * @param Charcoal_String $key   Key of the value 
	 * @param mixed $value   Value to set
	 * @param Charcoal_Boolean $skip_filters   If TRUE, skip all registered filters
	 *
	 * @return mixed
	 */
	public function set( Charcoal_String $key, $value, Charcoal_Boolean $skip_filters = NULL );

	/*
	 *	Merge all elements of an array into container
	 *
	 * @param array $array   Array to merge
	 */
	public function setArray( array $array );

	/*
	 *	Set all elements in a Properties object into container. All of container elements will be overwrited.
	 *
	 * @param Charcoal_Properties $data   Properties object to set
	 */
	public function setProperties( Charcoal_Properties $data );

	/*
	 *	Merge all elements in a Properties object into container
	 *
	 * @param Charcoal_Properties $data   Properties object to merge
	 * @param Charcoal_Boolean $overwrite   If TRUE, container values will be overwrited by properties data.
	 */
	public function mergeProperties( Charcoal_Properties $data, Charcoal_Boolean $overwrite = NULL );

	/*
	 * Copy parameters from request object. All of container elements will be overwrited.
	 *
	 * @param Charcoal_IRequest $data   Request object to set
	 */
	public function setFromRequest( Charcoal_IRequest $data );

}

return __FILE__;
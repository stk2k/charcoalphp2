<?php
/**
* リクエストを定義するインタフェース
*
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
interface Charcoal_ISequence extends Charcoal_IProperties
{
	/**
	 *  Get a global parameter
	 *
	 * @param string $key            Key string to get
	 *
	 * @return mixed
	 */
	public function getGlobal( $key );

	/**
	 *  Get a local parameter
	 *
	 * @param string $key            Key string to get
	 *
	 * @return mixed
	 */
	public function getLocal( $key );

	/**
	 *  set a parameter
	 *
	 * @param string $key            Key string to get
	 * @param mixed $value           value to set
	 */
	public function set( $key, $value );

	/**
	 *  set a global parameter
	 *
	 * @param string $key            Key string to get
	 * @param mixed $value           value to set
	 */
	public function setGlobal( $key, $value );

	/**
	 *  set a local parameter
	 *
	 * @param string $key            Key string to get
	 * @param mixed $value           value to set
	 */
	public function setLocal( $key, $value );



}


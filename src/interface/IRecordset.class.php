<?php
/**
* Interface of recordset
* 
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IRecordset extends Iterator
{
	const FETCHMODE_NUM = 1;
	const FETCHMODE_ASSOC = 2;
	const FETCHMODE_BOTH  = 3;
	const FETCHMODE_INTO  = 4;
	const FETCHMODE_CLASS  = 5;
	const FETCHMODE_COLUMN  = 6;

	/**
	 * Set fetch mode
	 *
	 * @param integer $mode         fetch mode(self::FETCHMODE_XXX)
	 * @param mixed $options        option parameters
	 */
	public function setFetchMode( $mode, $options );

	/**
	 * fetch record
	 *
	 * @param integer $mode         fetch style(self::FETCHMODE_XXX)
	 */
	public function fetch( $mode );

	/**
	 * Implementation method of IteratorAggregate interface
	 *
	 * @return Traversable    iterator object
	 */
	public function getIterator();
}


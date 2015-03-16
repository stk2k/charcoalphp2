<?php
/**
* Interface of recordset factory
* 
* PHP version 5
*
* @package    interfaces
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
interface Charcoal_IRecordsetFactory
{
	/**
	 * create recordset
	 *
	 * @param mixed $result         query result
	 */
	public function createRecordset( $result, $fetch_mode, $options );
}


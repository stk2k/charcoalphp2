<?php
/**
* data source for PDO
*
* PHP version 5
*
* @package    objects.data_sources
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_PDORecordsetFactory implements Charcoal_IRecordsetFactory
{
	/**
	 * create recordset
	 *
	 * @param mixed $result         query result
	 */
	public function createRecordset( $result, $fetch_mode, $options )
	{
		return new Charcoal_PDORecordset( $result, $fetch_mode, $options );
	}
}


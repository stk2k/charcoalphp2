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
	private $fetch_mode;
	private $options;

	/*
	 *	コンストラクタ
	 */
	public function __construct( $fetch_mode, $options = NULL )
	{
		$this->fetch_mode = $fetch_mode;
		$this->options = $options;
	}

	/**
	 * create recordset
	 *
	 * @param mixed $result         query result
	 *
	 * @return Charcoal_IRecordset      recordset object
	 */
	public function createRecordset( $result )
	{
		return new Charcoal_PDORecordset( $result, $this->fetch_mode, $this->options );
	}
}


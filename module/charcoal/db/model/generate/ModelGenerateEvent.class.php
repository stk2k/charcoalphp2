<?php
/**
* タスク
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class ModelGenerateEvent extends Charcoal_Event
{
	private $table;
	private $target_dir;

	/**
	 *	constructor
	 *	
	 *	@param string|Charcoal_String $table          table name
	 *	@param string|Charcoal_String $target_dir     target directory
	 */
	public function __construct( $table, $target_dir )
	{
		parent::__construct();

		$this->table        = us($table);
		$this->target_dir   = us($target_dir);
	}

	/**
	 * get table name
	 *
	 * @return string    table name
	 */
	public function getTable()
	{
		return $this->table;
	} 

	/**
	 * get target directory
	 *
	 * @return string    target directory
	 */
	public function getTargetDir()
	{
		return $this->target_dir;
	} 

}

return __FILE__;
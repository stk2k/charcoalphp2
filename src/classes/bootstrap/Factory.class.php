<?php
/**
* ファクトリクラス
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_Factory extends Charcoal_Object
{
	private $config_provider;
	private $sandbox;

	/**
	 *  Constructor
	 */
	public function __construct( $sandbox )
	{
		Charcoal_ParamTrait::checkSandbox( 1, $sandbox );

		$this->sandbox = $sandbox;

		parent::__construct();
	}

}


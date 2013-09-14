<?php
/**
* Frontend interface of class loader
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_ClassLoader
{
	private $_loaders;

	/**
	 *  constructor
	 */
	private function __construct()
	{
		$this->_loaders = array();
	}

	/*
	 *  get singleton instance
	 */
	public static function getInstance()
	{
		static $singleton_;
		if ( $singleton_ == null ){
			$singleton_ = new Charcoal_ClassLoader();
		}
		return $singleton_;
	}

	/**
	 *  add class loader
	 */
	public static function addClassLoader( Charcoal_IClassLoader $loader )
	{
		// インスタンスの取得
		$ins = self::getInstance();

		$ins->_loaders[] = $loader;
	}

	/**
	 * load a class by name
	 */
	public static function loadClass( $class_name )
	{
//		log_debug( "debug,class_loader", "class_loader", "required loading class: [$class_name]" );

		$ins = self::getInstance();

		// callback all class loaders
		$loaders = $ins->_loaders;
		foreach( $loaders as $loader ){
//			$loader_name = $loader->getObjectName();

			if ( $loader->loadClass( s($class_name) ) ){
//				log_debug( "debug,class_loader", "class_loader", "class($class_name) loaded by loader[$loader_name]" );
				return TRUE;
			}
		}

		return FALSE;
	}
}

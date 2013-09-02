<?php
/**
* Array Routing Rule
*
* PHP version 5
*
* @package    url_mappers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
class Charcoal_ArrayRoutingRule extends Charcoal_CharcoalObject implements Charcoal_IRoutingRule
{
	var $_proc_paths;

	/*
	 * Construct object
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config )
	{
		$rules_section = $config->getSection( s('routing rules') );

		$patterns = $rules_section->getKeys();

		foreach( $patterns as $pattern ){
			$proc_path = $rules_section->getString( s($pattern) );
			if ( $proc_path === NULL ){
				_throw( new Charcoal_ObjectConfigException( $this, s($pattern), ('can not be NULL') ) );
			}
			$this->_proc_paths[$pattern] = us($proc_path);
		}
			
		log_info( 'system,debug,router', "_proc_paths:" . print_r($this->_proc_paths,true) );
	}

	/*
	 *  Get all keys
	 *
	 * @return array  
	 */
	public function getKeys()
	{
		return array_keys( $this->_proc_paths );
	}

	/*
	 *  Get procedure path associated with a pattern
	 *
	 * @return string
	 */
	public function getProcPath( Charcoal_String $pattern )
	{
		$pattern = us($pattern);

		return isset($this->_proc_paths[$pattern]) ? $this->_proc_paths[$pattern] : NULL;
	}

}

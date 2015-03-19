<?php
/**
* SimpleHtmlDom Component
*
* PHP version 5
*
* @package    component.rss.simplepie
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
require_once( 'SimpleHtmlDomElement.class.php' );
require_once( 'SimpleHtmlDomComponentException.class.php' );

require_once( 'simple_html_dom.php' );

class Charcoal_SimpleHtmlDomComponent extends Charcoal_CharcoalComponent implements Charcoal_IComponent
{
	private $simple_html_dom;

	/**
	 *  Constructor
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
	public function configure( $config )
	{
		parent::configure( $config );

	}

	/**
	 * create from URL
	 * 
	 * @param Charcoal_String|string $text    HTML text
	 */
	public function createFromString( $text )
	{
		$this->simple_html_dom = str_get_html( us($text) );
	}

	/**
	 * create from local file
	 * 
	 * @param Charcoal_String|string $url    URL
	 */
	public function createFromFile( $file )
	{
		$this->simple_html_dom = file_get_html( us($file) );
	}

	/**
	 * create from URL
	 * 
	 * @param Charcoal_String|string $url    URL
	 */
	public function createFromURL( $url )
	{
		$this->simple_html_dom = file_get_html( us($url) );
	}

	/**
	 * clear
	 * 
	 * @param Charcoal_String|string $url    URL
	 */
	public function clear()
	{
		if ( $this->simple_html_dom ){
			$this->simple_html_dom->clear();
			$this->simple_html_dom = NULL;
		}
	}

	/**
	 * find elements
	 * 
	 * @param Charcoal_String|string $selector
	 * @param Charcoal_Integer|integer $index
	 */
	public function find( $selector, $index = NULL )
	{
		Charcoal_ParamTrait::validateString( 1, $selector );
		Charcoal_ParamTrait::validateInteger( 2, $index, TRUE );

		if ( !$this->simple_html_dom ){
			_throw( new SimpleHtmlDomComponentException("SimpleHtmlDom object is not created") );
		}

		$selector = us($selector);

			log_debug( "debug", "index:$index" );
		if ( $index !== NULL ){
			// returns single element
			$index = ui($index);
			log_debug( "debug", "returns single element" );
			$result = $this->simple_html_dom->find( $selector, $index );
			log_debug( "debug", "result: " . print_r($result,true) );
			return $result ? new Charcoal_SimpleHtmlDomElement( $result ) : NULL;
		}

		// returns all elements
			log_debug( "debug", "selector:" . print_r($selector,true) );
		$result = $this->simple_html_dom->find( $selector );
			log_debug( "debug", "result: " . print_r($result,true) );
		$elements = array();
		foreach( $result as $e ){
			$elements[] = new Charcoal_SimpleHtmlDomElement( $e );
		}
		return $result ? $elements : array();
	}

}


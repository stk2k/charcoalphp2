<?php
/**
* SimplePie RSS Channel
*
* PHP version 5
*
* @package    component.rss.simplepie
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_SimpleHtmlDomElement extends Charcoal_Object
{
	private $element;

	/**
	 *  Constructor
	 *
	 * @param simple_html_dom_node $element
	 */
	public function __construct( $element )
	{
		Charcoal_ParamTrait::checkIsA( 1, 'simple_html_dom_node', $element );

		parent::__construct();

		$this->element = $element;
	}

	/**
	 * Returns inner text
	 *
	 * @return string 
	 */
	public function getInnerText()
	{
		return $this->element->innertext;
	}

	/**
	 * Returns outer text
	 *
	 * @return string 
	 */
	public function getOuterText()
	{
		return $this->element->outertext;
	}

	/**
	 * Returns tag
	 *
	 * @return string 
	 */
	public function getTag()
	{
		return $this->element->tag;
	}

	/**
	 * find elements
	 * 
	 * @param Charcoal_String|string $selector
	 * @param Charcoal_Integer|integer $index
	 */
	public function find( $selector, $index = NULL )
	{
		Charcoal_ParamTrait::checkString( 1, $selector );
		Charcoal_ParamTrait::checkInteger( 2, $index, TRUE );

		$selector = us($selector);

		if ( $index !== NULL ){
			// returns single element
			$index = ui($index);
			$result = $this->element->find( $selector, $index );
			return $result ? new Charcoal_SimpleHtmlDomElement( $result ) : NULL;
		}

		// returns all elements
		$result = $this->element->find( $selector );
		$elements = array();
		foreach( $result as $e ){
			$elements[] = new Charcoal_SimpleHtmlDomElement( $e );
		}
		return $result ? $elements : array();
	}

}


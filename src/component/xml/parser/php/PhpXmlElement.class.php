<?php
/**
* xml element
*
* PHP version 5
*
* @package    component.xml.parser.php
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2012 CharcoalPHP Development Team
*/

class PhpXmlElement extends Charcoal_Object
{
	const MAX_TEXT_DISPLAY_LENGTH      = 100;

	private $parser;
	private $name;
	private $attrs;
	private $text;

	/**
	 *  Constructor
	 *
	 * @param string $name
	 * @param array $attrs
	 */
	public function __construct( Charcoal_PhpXmlParserComponent $parser, $name, $attrs )
	{
		parent::__construct();

		$this->parser = $parser;
		$this->name = $name;
		$this->attrs = $attrs;
	}

	/**
	* get name
	*
	* @return string           name of this element
	*/
	public function getName(){
		return $this->name;
	}

	/**
	* get attributes
	*
	* @return array           attributes
	*/
	public function getAttributes(){
		return $this->attrs;
	}

	/**
	* get text
	*
	* @return string           text
	*/
	public function getText(){
		return $this->text;
	}

	/**
	* set text
	*
	* @param string $text           text
	*/
	public function setText( $text ){
		$this->text = $text;
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		$encoding = $this->parser->getEncoding();
		$output_encoding = $this->parser->getOutputEncoding();

		$max_length = self::MAX_TEXT_DISPLAY_LENGTH;
		$text = strlen($this->text) > $max_length ? substr($this->text,0,$max_length) . '...' : $this->text;
		$text = mb_convert_encoding($text, $output_encoding, $encoding);
		$attrs = array();
		if ( $this->attrs ){
			foreach( $this->attrs as $key=>$value ){
				$key = mb_convert_encoding($key, $output_encoding, $encoding);
				$value = mb_convert_encoding($value, $output_encoding, $encoding);
				$attrs[] = $key . '="' . $value . '"';
			}
		}
		return '[' . $this->name . ' ' . implode(' ',$attrs) . ']' . $text . '[/' . $this->name . ']';
	}
}


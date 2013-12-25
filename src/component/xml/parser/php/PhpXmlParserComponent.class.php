<?php
/**
* SimpleHtmlDom Component
*
* PHP version 5.3
*
* @package    component.xml.parser.php
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
require_once( 'PhpXmlElement.class.php' );
require_once( 'PhpXmlElementHandler.class.php' );
require_once( 'PhpXmlParserComponentException.class.php' );

class Charcoal_PhpXmlParserComponent extends Charcoal_CharcoalComponent implements Charcoal_IComponent
{
	private $parser;
	private $encoding;
	private $output_encoding;

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

		$encoding         = $config->getString( 'encoding', 'utf-8' );
		$case_folding     = $config->getBoolean( 'case_folding', true );
		$output_encoding  = $config->getString( 'output_encoding', 'utf-8' );

		$this->parser = xml_parser_create( us($encoding) );

		$this->encoding = $encoding;
		$this->output_encoding = $output_encoding;

		xml_parser_set_option( $this->parser, XML_OPTION_CASE_FOLDING, ub($case_folding) );

	}

	/**
	 * get encoding
	 * 
	 * @return Charcoal_String|string    encoding
	 */
	public function getEncoding()
	{
		return $this->encoding;
	}

	/**
	 * get output encoding
	 * 
	 * @return Charcoal_String|string    output encoding
	 */
	public function getOutputEncoding()
	{
		return $this->output_encoding;
	}

	/**
	 * set output encoding
	 * 
	 * @param Charcoal_String|string $encoding    output encoding
	 */
	public function setOutputEncoding( $encoding )
	{
		$this->output_encoding = $encoding;
	}

	/**
	 * create parser
	 * 
	 * @param Charcoal_String|string $encoding    encoding
	 */
	public function create( $encoding = NULL )
	{
		Charcoal_ParamTrait::checkString( 1, $selector, TRUE );

		$this->free();

		$this->parser = $encoding ? xml_parser_create( us($encoding) ) : xml_parser_create();
	}

	/**
	 * get option
	 * 
	 * @param Charcoal_Integer|integer $option    XML_OPTION_CASE_FOLDING or XML_OPTION_TARGET_ENCODING 
	 */
	public function getRawOption( $option )
	{
		Charcoal_ParamTrait::checkInteger( 1, $option );

		if ( !$this->parser ){
			_throw( new PhpXmlParserComponentException('parser object is not created') );
		}

		return xml_parser_get_option( $this->parser, ui($option) );
	}

	/**
	 * set option
	 * 
	 * @param Charcoal_Integer|integer $option    XML_OPTION_CASE_FOLDING or XML_OPTION_TARGET_ENCODING 
	 * @param mixed $value                        XML_OPTION_CASE_FOLDING or XML_OPTION_TARGET_ENCODING 
	 */
	public function setRawOption( $option, $value )
	{
		Charcoal_ParamTrait::checkInteger( 1, $option );

		if ( !$this->parser ){
			_throw( new PhpXmlParserComponentException('parser object is not created') );
		}

		return xml_parser_set_option( $this->parser, ui($option), $value );
	}

	/**
	 * set element handler
	 * 
	 * @param PhpXmlElementHandler $handler    tag start handler
	 */
	public function setElementHandler( $handler )
	{
		if ( !$this->parser ){
			_throw( new PhpXmlParserComponentException('parser object is not created') );
		}

		$elements = array();

		$parser = $this;

		xml_set_element_handler(
				$this->parser, 
				function( $p , $name , $attribs ) use(&$elements, $handler, $parser){
					$element = new PhpXmlElement( $parser, $name , $attribs );
					array_push( $elements, $element );
					if ( $handler ){
						$handler->onXmlElementStart( $element );
					}
				},
				function( $p , $name ) use(&$elements, $handler){
					$element = array_pop( $elements );
					if ( $element && $handler ){
						$handler->onXmlElementEnd( $element );
					}
				}
			);

		xml_set_character_data_handler(
				$this->parser, 
				function( $p , $data ) use(&$elements){
					$element = array_pop( $elements );
					if ( $element ){
						$element->setText( $data );
					}
					array_push( $elements, $element );
				}
			);
	}

	/**
	 * set raw element handler
	 * 
	 * @param callable $start_handler    tag start handler
	 * @param callable $end_handler    tag end handler
	 */
	public function setRawElementHandler( $start_handler, $end_handler )
	{
		if ( !$this->parser ){
			_throw( new PhpXmlParserComponentException('parser object is not created') );
		}
		xml_set_element_handler( $this->parser, $start_handler, $end_handler );
	}

	/**
	 * set raw character data handler(CDATA)
	 * 
	 * @param callable $handler    character data handler
	 */
	public function setRawCharacterDataHandler( $handler )
	{
		if ( !$this->parser ){
			_throw( new PhpXmlParserComponentException('parser object is not created') );
		}
		xml_set_character_data_handler( $this->parser, $handler );
	}

	/**
	 * free parser
	 * 
	 */
	public function free()
	{
		if ( $this->parser ){
			xml_parser_free( $this->parser );
			$this->parser = NULL;
		}
		$this->handler = NULL;
	}

	/**
	 * parse XML
	 * 
	 * @param Charcoal_String|string $data         Chunk of data to parse
	 * @param Charcoal_Boolean|bool $is_final      If set and TRUE, data is the last piece of data sent in this parse. 
	 * 
	 * @return bool                  Returns 1 on success or 0 on failure. 
	 */
	public function parse( $data, $is_final = false )
	{
		Charcoal_ParamTrait::checkString( 1, $data );
		Charcoal_ParamTrait::checkBoolean( 2, $is_final );

		if ( !$this->parser ){
			_throw( new PhpXmlParserComponentException('parser object is not created') );
		}

		return xml_parse( $this->parser, us($data), ub($is_final) );
	}

}


<?php
/**
* PhpTidy Component
*
* PHP version 5.3
*
* @package    component.xml.parser.php
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
require_once( 'TidyNode.class.php' );
require_once( 'TidyComponentException.class.php' );

class Charcoal_TidyComponent extends Charcoal_CharcoalComponent implements Charcoal_IComponent
{
	private $tidy;
	private $config;
	private $encoding;

	/**
	 *  Constructor
	 */
	public function __construct()
	{
		parent::__construct();
/*
		if ( !extension_loaded('tidy') ) {
			_throw( new Charcoal_ExtensionNotLoadedException('tidy') );
		}
*/
		$this->tidy = new Tidy();
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		$encoding       = $config->getString( 'encoding', 'utf8' );
		$indent         = $config->getBoolean( 'indent', true );
		$output_xhtml   = $config->getBoolean( 'output-xhtml', true );
		$output_html    = $config->getBoolean( 'output-html', true );
		$wrap           = $config->getInteger( 'wrap', 200 );
		$show_body_only = $config->getBoolean( 'show-body-only', true );
		$clean          = $config->getBoolean( 'clean', false );

		$this->config = array(
				'indent' => ub($indent),
				'output-xhtml' => ub($output_xhtml),
				'output-html' => ub($output_html),
				'wrap' => ui($wrap),
				'show-body-only' => ub($show_body_only),
				'clean' => ub($clean),

			);

		$this->encoding = us($encoding);
	}

	/**
	 * parse HTML from string
	 * 
	 * @param Charcoal_String|string $input       HTML text to parse
	 */
	public function parseString( $input )
	{
		Charcoal_ParamTrait::validateString( 1, $input );

		$this->tidy->parseString( us($input), $this->config, $this->encoding );
	}

	/**
	 * parse HTML from file
	 * 
	 * @param Charcoal_String|string $file                 HTML file to parse
	 * @param Charcoal_Booolean|bool $use_include_path     use include path
	 */
	public function parseFile( $file, $use_include_path = FALSE )
	{
		Charcoal_ParamTrait::validateString( 1, $file );

		$this->tidy->parseFile( $us($file), $this->config, $this->encoding, $use_include_path );
	}

	/**
	 * repair HTML from string
	 * 
	 * @param Charcoal_String|string $file                 HTML file to parse
	 * @param Charcoal_Booolean|bool $use_include_path     use include path
	 * 
	 * @return string       Returns the repaired string.
	 */
	public function repairString( $input )
	{
		Charcoal_ParamTrait::validateString( 1, $input );

		return $this->tidy->repairString( us($input), $this->config, $this->encoding );
	}

	/**
	 * repair HTML from file
	 * 
	 * @param Charcoal_String|string $input       HTML text to parse
	 * 
	 * @return string       Returns the repaired string.
	 */
	public function repairFile( $file, $use_include_path = FALSE )
	{
		Charcoal_ParamTrait::validateString( 1, $file );

		return $this->tidy->repairFile( us($file), $this->config, $this->encoding, $use_include_path );
	}

	/**
	 * purify HTML
	 * 
	 * @return bool                 Returns TRUE on success or FALSE on failure.
	 */
	public function cleanRepair()
	{
		return $this->tidy->cleanRepair();
	}

	/**
	 * purified HTML
	 * 
	 * @return string                 Returns purified HTML.
	 */
	public function getResult()
	{
		return (string)$this->tidy;
	}

	/**
	 * Check if error has occurred
	 * 
	 * @return bool                 Returns TRUE whe some errors and warnings occurred or FALSE otherwise.
	 */
	public function hasErrors()
	{
		return !empty($this->tidy->errorBuffer);
	}

	/**
	 * Get error buffer
	 * 
	 * @return string                 Return warnings and errors which occurred parsing the specified document
	 */
	public function getErrorBuffer()
	{
		return $this->tidy->errorBuffer;
	}

	/*
	 * Returns a tidyNode object representing the root of the tidy parse tree
	 *
	 * @return Charcoal_TidyNode
	 */
	public function root()
	{
		return new Charcoal_TidyNode( $this->tidy->root() );
	}

	/*
	 * Returns a tidyNode object starting from the <head> tag of the tidy parse tree
	 *
	 * @return Charcoal_TidyNode
	 */
	public function head()
	{
		return new Charcoal_TidyNode( $this->tidy->head() );
	}

	/*
	 * Returns a tidyNode object starting from the <html> tag of the tidy parse tree
	 *
	 * @return Charcoal_TidyNode
	 */
	public function html()
	{
		return new Charcoal_TidyNode( $this->tidy->html() );
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string)$this->tidy;
	}
}


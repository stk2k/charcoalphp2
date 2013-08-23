<?php
/**
* HTMLデバッグトレスレンダラークラス
*
* PHP version 5
*
* @package    debug
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_HtmlDebugtraceRenderer extends Charcoal_CharcoalObject implements Charcoal_IDebugtraceRenderer
{
	/*
	 *	コンストラクタ
	 */
	public function __construct()
	{
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config )
	{
	}

	/**
	 * Print HTML Header
	 */
	private static function _makeHtmlHead( Exception $e, Charcoal_String $title )
	{
		$html = <<< HTML_HEADER
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="pragma" content="no-cache" />
<title>$title</title>
<style type="text/css">
  body {
    font-family: 'Verdana';
  }
  #charcoal{
    text-align: left;
    margin: 0px;
  }
  #charcoal h1 {
    line-height: 60px;
    font-size: 18px;
    width: 100%;
    background-color: bisque;
    color: coral;
  }
  #charcoal h2 {
    line-height: 30px;
    font-size: 13px;
    width: 100%;
    background-color: lightsteelblue;
    color: mediumblue;
  }
  #charcoal table {
    width: 100%;
    border-left: 1px silver solid;
    border-top: 1px silver solid;
    margin-top: 2px;
  }
  #charcoal tr {
    height: 30px;
  }
  #charcoal th.no {
    width: 25px;
    border-right: 1px silver solid;
    border-bottom: 1px silver solid;
    background-color: bisque;
    color: coral;
  }
  #charcoal td.title {
    background-color: mistyrose;
    color: darkslategray;
    font-size: 13px;
  }
  #charcoal td.message {
    background-color: seashell;
    color: darkslategray;
    font-size: 12px;
    font-weight: bold;
  }
  #charcoal td.title, td.message {
    border-right: 1px silver solid;
    border-bottom: 1px silver solid;
  }
  #charcoal .value {
    margin: 5px;
  }
/* Source Code */
.source_code {
  font-family: 'Courier New', Arial, Tahoma, Verdana;
  font-size: 10pt;
}

.line_no {
  width: 50px;
  color: blue;
  font-weight: bold;
  text-align: right;
  margin-right: 10px;
}

.even {
  background-color: #EEFEEF;
}

.odd {
  background-color: #DDEDDE;
}

.keyword {
  color: orange;
  font-weight: bold;
}

.comment {
  color: green;
  font-weight: bold;
}

.identifier {
  color: teal;
  font-weight: bold;
}

.const_string {
  color: darkblue;
  font-weight: bold;
}
</style>
<script type="text/javascript">
function expand( id )
{
	var div = document.getElementById(id);
	if ( div ){
		div.style.display = div.style.display ? "" : "none";
	}

	return false;
}
</script>
HTML_HEADER;
		return $html;
	}

	/**
	 * check PHP core interfaces
	 */
	private static function _isCoreInterfaces( $class_name )
	{
		$core_classes = array(
				"ArrayAccess",
				"Countable",
				"Iterator",
				"IteratorAggregate",
				"OuterIterator",
				"RecursiveIterator",
				"Reflector",
				"SeekableIterator",
				"Serializable",
				"SplObserver",
				"SplSubject",
				"Traversable",
			);

		return in_array($class_name,$core_classes);
	}

	/**
	 * check PHP core class
	 */
	private static function _isCoreClass( $class_name )
	{
		$core_classes = array(
				"stdClass",
				"Exception",
				"ErrorException",
				"Closure",
				"COMPersistHelper",
				"com_exception",
				"com_safearray_proxy",
				"variant",
				"com",
				"dotnet",
				"DateTime",
				"DateTimeZone",
				"DateInterval",
				"DatePeriod",
				"LogicException",
				"BadFunctionCallException",
				"BadMethodCallException",
				"DomainException",
				"InvalidArgumentException",
				"LengthException",
				"OutOfRangeException",
				"RuntimeException",
				"OutOfBoundsException",
				"OverflowException",
				"RangeException",
				"UnderflowException",
				"UnexpectedValueException",
				"RecursiveIteratorIterator",
				"IteratorIterator",
				"FilterIterator",
				"RecursiveFilterIterator",
				"ParentIterator",
				"LimitIterator",
				"CachingIterator",
				"RecursiveCachingIterator",
				"NoRewindIterator",
				"AppendIterator",
				"InfiniteIterator",
				"RegexIterator",
				"RecursiveRegexIterator",
				"EmptyIterator",
				"RecursiveTreeIterator",
				"ArrayObject",
				"ArrayIterator",
				"RecursiveArrayIterator",
				"SplFileInfo",
				"DirectoryIterator",
				"FilesystemIterator",
				"RecursiveDirectoryIterator",
				"GlobIterator",
				"SplFileObject",
				"SplTempFileObject",
				"SplDoublyLinkedList",
				"SplQueue",
				"SplStack",
				"SplHeap",
				"SplMinHeap",
				"SplMaxHeap",
				"SplPriorityQueue",
				"SplFixedArray",
				"SplObjectStorage",
				"MultipleIterator",
				"ReflectionException",
				"Reflection",
				"ReflectionFunctionAbstract",
				"ReflectionFunction",
				"ReflectionParameter",
				"ReflectionMethod",
				"ReflectionClass",
				"ReflectionObject",
				"ReflectionProperty",
				"ReflectionExtension",
				"__PHP_Incomplete_Class",
				"php_user_filter",
				"Directory",
				"ZipArchive",
				"LibXMLError",
				"DOMException",
				"DOMStringList",
				"DOMNameList",
				"DOMImplementationList",
				"DOMImplementationSource",
				"DOMImplementation",
				"DOMNode",
				"DOMNameSpaceNode",
				"DOMDocumentFragment",
				"DOMDocument",
				"DOMNodeList",
				"DOMNamedNodeMap",
				"DOMCharacterData",
				"DOMAttr",
				"DOMElement",
				"DOMText",
				"DOMComment",
				"DOMTypeinfo",
				"DOMUserDataHandler",
				"DOMDomError",
				"DOMErrorHandler",
				"DOMLocator",
				"DOMConfiguration",
				"DOMCdataSection",
				"DOMDocumentType",
				"DOMNotation",
				"DOMEntity",
				"DOMEntityReference",
				"DOMProcessingInstruction",
				"DOMStringExtend",
				"DOMXPath",
				"PDOException",
				"PDO",
				"PDOStatement",
				"PDORow",
				"SimpleXMLElement",
				"SimpleXMLIterator",
				"XMLReader",
				"XMLWriter",
				"PharException",
				"Phar",
				"PharData",
				"PharFileInfo",
				"mysqli_sql_exception",
				"mysqli_driver",
				"mysqli",
				"mysqli_warning",
				"mysqli_result",
				"mysqli_stmt",
				"SoapClient",
				"SoapVar",
				"SoapServer",
				"SoapFault",
				"SoapParam",
				"SoapHeader",
				"SQLiteDatabase",
				"SQLiteResult",
				"SQLiteUnbuffered",
				"SQLiteException",
				"SQLite3",
				"SQLite3Stmt",
				"SQLite3Result",
			);

		return in_array($class_name,$core_classes);
	}

	/**
	 * Print HTML Body
	 */
	private static function _makeHtmlBody( Exception $e, Charcoal_String $title, Charcoal_String $file, Charcoal_Integer $line )
	{
		$echo = Charcoal_Framework::testEchoFlag( i(Charcoal_EnumEchoFlag::ECHO_DEBUGTRACE_RENDERER) );

		if ( $echo ){
			echo "[debugtrace_renderer] " . __CLASS__ . "#_makeHtmlBody(" . get_class($e) . ", '$title', $file, $line) start" . eol();
		}

		$html = '';

		$html .= '<div id="charcoal">' . PHP_EOL;
		$html .= '<h1><div class="value">' . $title . '</div></h1>' . PHP_EOL;

		// output defined interfaces
		if ( $echo ){
			echo "[debugtrace_renderer] output defined interfaces" . eol();
		}

		$declared_interfaces = get_declared_interfaces();
		$interfaces = NULL;
		foreach( $declared_interfaces as $interface )
		{
			if ( !self::_isCoreInterfaces($interface) ){
				$interfaces[] = $interface;
			}
		}
		sort($interfaces);

		$html .= '<h2><div class="value">Declared Interfaces&nbsp;&nbsp;<a href="#" onclick="expand(\'declared_interfaces\');">(' . count($interfaces) . ')</a></div></h2>' . PHP_EOL;

		$html .= '' . PHP_EOL;
		$html .= '<table cellspacing="0" cellpadding="0" id="declared_interfaces" style="display:none">' . PHP_EOL;
		$no = 1;
		foreach( $interfaces as $interface )
		{
			$html .= '<tr>' . PHP_EOL;
			$html .= '  <th class="no">' . $no . '</th>' . PHP_EOL;
			$html .= '  <td class="title"><span class="value">' . $interface . '</span></td>' . PHP_EOL;
			$html .= '</tr>' . PHP_EOL;

			$no ++;
		}
		$html .= '</table>' . PHP_EOL;

		// output defined classes
		if ( $echo ){
			echo "[debugtrace_renderer] output defined classes" . eol();
		}

		$declared_klasses = get_declared_classes();
		$klasses = NULL;
		foreach( $declared_klasses as $klass )
		{
			if ( !self::_isCoreClass($klass) ){
				$klasses[] = $klass;
			}
		}
		sort($klasses);

		$html .= '<h2><div class="value">Declared Classes&nbsp;&nbsp;<a href="#" onclick="expand(\'declared_classes\');">(' . count($klasses) . ')</a></div></h2>' . PHP_EOL;

		$html .= '' . PHP_EOL;
		$html .= '<table cellspacing="0" cellpadding="0" id="declared_classes" style="display:none">' . PHP_EOL;
		$no = 1;
		foreach( $klasses as $klass )
		{
			$html .= '<tr>' . PHP_EOL;
			$html .= '  <th class="no">' . $no . '</th>' . PHP_EOL;
			$html .= '  <td class="title"><span class="value">' . $klass . '</span></td>' . PHP_EOL;
			$html .= '</tr>' . PHP_EOL;

			$no ++;
		}
		$html .= '</table>' . PHP_EOL;

		// output loaded files
		if ( $echo ){
			echo "[debugtrace_renderer] output loaded files" . eol();
		}

		$files = Charcoal_Framework::getLoadedSourceFiles();
		$html .= '<h2><div class="value">Loaded Source Files&nbsp;&nbsp;<a href="#" onclick="expand(\'source_files\');">(' . count($files) . ')</a></div></h2>' . PHP_EOL;

		$html .= '' . PHP_EOL;
		$html .= '<table cellspacing="0" cellpadding="0" id="source_files" style="display:none">' . PHP_EOL;
		$no = 1;
		foreach( $files as $file )
		{
			$html .= '<tr>' . PHP_EOL;
			$html .= '  <th class="no">' . $no . '</th>' . PHP_EOL;
			$html .= '  <td class="title"><span class="value">' . $file . '</span></td>' . PHP_EOL;
			$html .= '</tr>' . PHP_EOL;

			$no ++;
		}
		$html .= '</table>' . PHP_EOL;

		// output exception stack
		if ( $echo ){
			echo "[debugtrace_renderer] output exception stack" . eol();
		}

		$html .= '<h2><div class="value">Exception Stack</div></h2>' . PHP_EOL;

		$hash = $file->hash() . $line->hash();

		$src = new Charcoal_PhpSourceInfo( s($file), i($line), i(10) );
		$html .= '<div style="text-align: left;">' . PHP_EOL;
		$html .= '    <span class="value">' . $file . '(' . $line . ')' . '&nbsp;<a href="#" onclick="return expand(\'' . $hash .'\')">View Source</a></span>' . PHP_EOL;
		$html .= '    <div class="value" id="' . $hash . '" style="display:none">' . $src . '</div>' . PHP_EOL;
		$html .= '</div>' . PHP_EOL;

		$html .= '<table cellspacing="0" cellpadding="0">' . PHP_EOL;
		$no = 1;
		$backtrace = NULL;
		while( $e )
		{
			$clazz = get_class($e);
			$file = $e->getFile();
			$line = $e->getLine();
			$message = $e->getMessage();
			$backtrace = ($e instanceof Charcoal_CharcoalException) ? $e->getBackTrace() : NULL;

			$src = new Charcoal_PhpSourceInfo( s($file), i($line), i(10) );

			$hash = s($file)->hash() . i($line)->hash();

			$src_id = "'src" . $no . "'";

			$html .= '<tr>' . PHP_EOL;
			$html .= '  <th class="no" rowspan="3">' . $no . '</th>' . PHP_EOL;
			$html .= '  <td class="title">' . PHP_EOL;
			$html .= '    <span class="value">class:' . $clazz . '</span>' . PHP_EOL;
			$html .= '    <span class="value">file:' . $file . '(' . $line . ')</span>' . PHP_EOL;
			$html .= '  </td>' . PHP_EOL;
			$html .= '</tr>' . PHP_EOL;
			$html .= '<tr>' . PHP_EOL;
			$html .= '  <td class="message"><div class="value">' . $message . '</div></td>' . PHP_EOL;
			$html .= '</tr>' . PHP_EOL;
			$html .= '<tr>' . PHP_EOL;
			$html .= '  <td class="message"><div class="value"><a href="#" onclick="return expand(\'' . $hash .'\')">View Source</a></span></div>' . PHP_EOL;
			$html .= '    <div class="value" id="' . $hash . '" style="display:none">' . $src . '</div>' . PHP_EOL;
			$html .= '  </td>' . PHP_EOL;
			$html .= '</tr>' . PHP_EOL;

			$e = $e->getPrevious();
			$no ++;
		}
		$html .= '</table>' . PHP_EOL;

		if ( $backtrace === NULL || !is_array($backtrace) ){
			return $html;
		}

		// output call stack
		if ( $echo ){
			echo "[debugtrace_renderer] output call stack" . eol();
		}

		$html .= '<h2><div class="value">Call Stack</div></h2>' . PHP_EOL;

		$html .= '<table cellspacing="0" cellpadding="0">' . PHP_EOL;
		$call_no = 1;
		foreach( $backtrace as $element ){
			$klass = isset($element['class']) ? $element['class'] : '';
			$func  = isset($element['function']) ? $element['function'] : '';
			$type  = isset($element['type']) ? $element['type'] : '';
			$args  = isset($element['args']) ? $element['args'] : array();
			$file  = isset($element['file']) ? $element['file'] : '';
			$line  = isset($element['line']) ? $element['line'] : '';

			if ( $type == "::" ){
				$ref_method = new ReflectionMethod( $klass, $func );
				$modifiers = Reflection::getModifierNames( $ref_method->getModifiers() );
				$modifiers = implode(" ",$modifiers);
				$params = $ref_method->getParameters();
/*
				$args_disp = '';
				foreach( $params as $p ){
					if ( strlen($args_disp) > 0 ){
						$args_disp .= ',';
					}
					if ( $p->isOptional() ){
						$args_disp .= '[';
					}
					if ( $p->isArray() ){
						$args_disp .= 'array ';
					}
					$args_disp .= $p->getClass();
					if ( $p->isPassedByReference() ){
						$args_disp .= '&amp;';
					}
					$args_disp .= $p->getName();
					if ( $p->isDefaultValueAvailable()||$p->isDefaultValueConstant() ){
						$args_disp .= '=' . $p->getDefaultValue();
					}
					if ( $p->isOptional() ){
						$args_disp .= ']';
					}
				}*/

				$message = "$modifiers {$klass}{$type}{$func}($args_disp)";
			}
			else{
				$args_disp = '';
				foreach( $args as $arg ){
					if ( strlen($args_disp) > 0 ){
						$args_disp .= ',';
					}
					$args_disp .= '"' . Charcoal_System::toString($arg) . '"';
				}

				$message = "{$klass}{$type}{$func}($args_disp)";
			}

			$src = new Charcoal_PhpSourceInfo( s($file), i($line), i(10) );

			$hash = s($file)->hash() . i($line)->hash();

			$src_id = "'src" . $call_no . "'";

			$html .= '<tr>' . PHP_EOL;
			$html .= '  <th class="no" rowspan="3">' . $call_no . '</th>' . PHP_EOL;
			$html .= '  <td class="title">' . PHP_EOL;
			$html .= '    <span class="value">class:' . $klass . '</span>' . PHP_EOL;
			$html .= '    <span class="value">file:' . $file . '(' . $line . ')</span>' . PHP_EOL;
			$html .= '    <a name="' . $hash . '"></a>' . PHP_EOL;
			$html .= '  </td>' . PHP_EOL;
			$html .= '</tr>' . PHP_EOL;
			$html .= '<tr>' . PHP_EOL;
			$html .= '  <td class="message"><div class="value">' . $message . '</div></td>' . PHP_EOL;
			$html .= '</tr>' . PHP_EOL;
			$html .= '<tr>' . PHP_EOL;
			$html .= '  <td class="message"><div class="value"><a href="#" onclick="return expand(\'' . $hash .'\')">View Source</a></span></div>' . PHP_EOL;
			$html .= '    <div class="value" id="' . $hash . '" style="display:none">' . $src . '</div>' . PHP_EOL;
			$html .= '  </td>' . PHP_EOL;
			$html .= '</tr>' . PHP_EOL;

			$call_no ++;
		}
		$html .= '</table>' . PHP_EOL;

		if ( $echo ){
			echo "[debugtrace_renderer] " . __CLASS__ . "#_makeHtmlBody(" . get_class($e) . ", '$title', $file, $line) end" . eol();
		}

		return $html;
	}

	/**
	 * Render debug trace
	 *
	 * @param Charcoal_String $title  title
	 */
	public function render( Exception $e, Charcoal_String $title = NULL )
	{
		list( $file, $line ) = Charcoal_System::caller(0);

		if ( $title === NULL ){
			$title = 'CharcoalPHP: Exception List';
		}

		echo $this->_output( $e, s($title), s($file), i($line) );
	}

	/**
	 * Output HTML
	 *
	 * @param Charcoal_String $title  title
	 */
	public function output( Exception $e, Charcoal_String $title = NULL )
	{
		$echo = Charcoal_Framework::testEchoFlag( i(Charcoal_EnumEchoFlag::ECHO_DEBUGTRACE_RENDERER) );

		if ( $echo ){
			echo "[debugtrace_renderer] " . __CLASS__ . "#output(" . get_class($e) . ", '$title'): " . eol();
		}

		list( $file, $line ) = Charcoal_System::caller(0);

		if ( $title === NULL ){
			$title = 'CharcoalPHP: Exception List';
		}

		return $this->_output( $e, s($title), s($file), i($line) );
	}

	/**
	 * Output HTML
	 *
	 * @param Charcoal_String $title  title
	 */
	private function _output( Exception $e, Charcoal_String $title, Charcoal_String $file, Charcoal_Integer $line )
	{
		$echo = Charcoal_Framework::testEchoFlag( i(Charcoal_EnumEchoFlag::ECHO_DEBUGTRACE_RENDERER) );

		if ( $echo ){
			echo "[debugtrace_renderer] " . __CLASS__ . "#_output(" . get_class($e) . ", '$title', $file, $line): " . eol();
		}

		$html  = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		$html .= '<html lang="ja">';
		$html .= '<head>';
		$html .= self::_makeHtmlHead( $e, s($title) );
		$html .= '</head>';
		$html .= '<body>' . PHP_EOL;
		$html .= self::_makeHtmlBody( $e, s($title), s($file), i($line) );
		$html .= '</body>' . PHP_EOL;
		$html .= '</html>' . PHP_EOL;

		if ( $echo ){
			echo "[debugtrace_renderer] html: $html" . eol();
		}

		return $html;
	}

}
return __FILE__;

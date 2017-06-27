<?php
/**
* HTMLデバッグトレスレンダラークラス
*
* PHP version 5
*
* @package    objects.debugtrace_renderers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_HtmlDebugtraceRenderer extends Charcoal_AbstractDebugtraceRenderer
{
    /** @var Charcoal_Boolean */
    private $clear_buffer;

    /**
     * Initialize instance
     *
     * @param array $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );
        
        $config = new Charcoal_HashMap($config);

        $this->clear_buffer = b( $config->getBoolean( 'clear_buffer', FALSE ) );
    }

    /**
     * Print HTML Header
     *
     * @param string $title
     *
     * @return string
     */
    private function makeHtmlHead( $title )
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
  #charcoal td.key {
    background-color: mistyrose;
    color: green;
    font-size: 13px;
    font-weight: bold;
  }
  #charcoal td.value {
    background-color: seashell;
    color: royalblue;
    font-size: 12px;
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
     * Print HTML Body
     *
     * @param Exception $e
     * @param string $title
     * @param string $file
     * @param integer $line
     *
     * @return string
     */
    private function makeHtmlBody( $e, $title, $file, $line )
    {
        $html = '';

        $html .= '<div id="charcoal">' . PHP_EOL;
        $html .= '<h1><div class="value">' . $title . '</div></h1>' . PHP_EOL;
    
        // Registry Items
        $registry_items = $this->getSandbox()->getRegistry()->dumpLoadedItems(TRUE);
        
        $html .= '<h2><div class="value">Registry Items&nbsp;&nbsp;<a href="#" onclick="expand(\'registry_items\');">(' . count($registry_items) . ')</a></div></h2>' . PHP_EOL;
    
        $html .= '' . PHP_EOL;
        $html .= '<table cellspacing="0" cellpadding="0" id="registry_items" style="display:none">' . PHP_EOL;
        $no = 1;
        foreach( $registry_items as $item )
        {
            $html .= '<tr>' . PHP_EOL;
            $html .= '  <th class="no">' . $no . '</th>' . PHP_EOL;
            $html .= '  <td class="key"><span class="value">' . $item . '</span></td>' . PHP_EOL;
            $html .= '</tr>' . PHP_EOL;
        
            $no ++;
        }
        $html .= '</table>' . PHP_EOL;

        // PHP info
        $phpinfo = array(
                'PHP_VERSION' => PHP_VERSION,
                'date_default_timezone' => date_default_timezone_get(),
            );

        $html .= '<h2><div class="value">PHP Info&nbsp;&nbsp;<a href="#" onclick="expand(\'phpinfo\');">(' . count($phpinfo) . ')</a></div></h2>' . PHP_EOL;

        $html .= '' . PHP_EOL;
        $html .= '<table cellspacing="0" cellpadding="0" id="phpinfo" style="display:none">' . PHP_EOL;
        $no = 1;
        foreach( $phpinfo as $name => $value )
        {
            $html .= '<tr>' . PHP_EOL;
            $html .= '  <th class="no" rowspan="2">' . $no . '</th>' . PHP_EOL;
            $html .= '  <td class="key"><span class="value">' . $name . '</span></td>' . PHP_EOL;
            $html .= '</tr>' . PHP_EOL;
            $html .= '<tr>' . PHP_EOL;
            $html .= '  <td class="value"><span class="value">' . $value . '</span></td>' . PHP_EOL;
            $html .= '</tr>' . PHP_EOL;

            $no ++;
        }
        $html .= '</table>' . PHP_EOL;

        // php.ini info
        $php_ini = ini_get_all();

        $html .= '<h2><div class="value">php.ini&nbsp;&nbsp;<a href="#" onclick="expand(\'php_ini\');">(' . count($php_ini) . ')</a></div></h2>' . PHP_EOL;

        $html .= '' . PHP_EOL;
        $html .= '<table cellspacing="0" cellpadding="0" id="php_ini" style="display:none">' . PHP_EOL;
        $no = 1;
        foreach( $php_ini as $key => $item )
        {
            $local_value = isset($item['local_value']) ? $item['local_value'] : NULL;

            $html .= '<tr>' . PHP_EOL;
            $html .= '  <th class="no" rowspan="2">' . $no . '</th>' . PHP_EOL;
            $html .= '  <td class="key"><span class="value">' . $key . '</span></td>' . PHP_EOL;
            $html .= '</tr>' . PHP_EOL;
            $html .= '<tr>' . PHP_EOL;
            $html .= '  <td class="value"><span class="value">' . $local_value . '</span></td>' . PHP_EOL;
            $html .= '</tr>' . PHP_EOL;

            $no ++;
        }
        $html .= '</table>' . PHP_EOL;

        // Framework info
        $frameworkinfo = array(
                'CharcoalPHP ver.' => Charcoal_Framework::getVersion(),

            );

        $html .= '<h2><div class="value">Framework Info&nbsp;&nbsp;<a href="#" onclick="expand(\'frameworkinfo\');">(' . count($phpinfo) . ')</a></div></h2>' . PHP_EOL;

        $html .= '' . PHP_EOL;
        $html .= '<table cellspacing="0" cellpadding="0" id="frameworkinfo" style="display:none">' . PHP_EOL;
        $no = 1;
        foreach( $frameworkinfo as $name => $value )
        {
            $html .= '<tr>' . PHP_EOL;
            $html .= '  <th class="no" rowspan="2">' . $no . '</th>' . PHP_EOL;
            $html .= '  <td class="key"><span class="value">' . $name . '</span></td>' . PHP_EOL;
            $html .= '</tr>' . PHP_EOL;
            $html .= '<tr>' . PHP_EOL;
            $html .= '  <td class="value"><span class="value">' . $value . '</span></td>' . PHP_EOL;
            $html .= '</tr>' . PHP_EOL;

            $no ++;
        }
        $html .= '</table>' . PHP_EOL;

        // $_SERVER variables

        $html .= '<h2><div class="value">$_SERVER variables&nbsp;&nbsp;<a href="#" onclick="expand(\'servervariables\');">(' . count($_SERVER) . ')</a></div></h2>' . PHP_EOL;

        $html .= '' . PHP_EOL;
        $html .= '<table cellspacing="0" cellpadding="0" id="servervariables" style="display:none">' . PHP_EOL;
        $no = 1;
        foreach( $_SERVER as $name => $value )
        {
            if ( is_array($value) ) $value = print_r($value,true);

            $html .= '<tr>' . PHP_EOL;
            $html .= '  <th class="no" rowspan="2">' . $no . '</th>' . PHP_EOL;
            $html .= '  <td class="key"><span class="value">' . $name . '</span></td>' . PHP_EOL;
            $html .= '</tr>' . PHP_EOL;
            $html .= '<tr>' . PHP_EOL;
            $html .= '  <td class="value"><span class="value">' . $value . '</span></td>' . PHP_EOL;
            $html .= '</tr>' . PHP_EOL;

            $no ++;
        }
        $html .= '</table>' . PHP_EOL;

        // $_COOKIE variables

        $html .= '<h2><div class="value">$_COOKIE variables&nbsp;&nbsp;<a href="#" onclick="expand(\'cookievariables\');">(' . count($_COOKIE) . ')</a></div></h2>' . PHP_EOL;

        $html .= '' . PHP_EOL;
        $html .= '<table cellspacing="0" cellpadding="0" id="cookievariables" style="display:none">' . PHP_EOL;
        $no = 1;
        foreach( $_COOKIE as $name => $value )
        {
            $html .= '<tr>' . PHP_EOL;
            $html .= '  <th class="no" rowspan="2">' . $no . '</th>' . PHP_EOL;
            $html .= '  <td class="key"><span class="value">' . $name . '</span></td>' . PHP_EOL;
            $html .= '</tr>' . PHP_EOL;
            $html .= '<tr>' . PHP_EOL;
            $html .= '  <td class="value"><span class="value">' . $value . '</span></td>' . PHP_EOL;
            $html .= '</tr>' . PHP_EOL;

            $no ++;
        }
        $html .= '</table>' . PHP_EOL;

        // $_ENV variables

        $html .= '<h2><div class="value">$_ENV variables&nbsp;&nbsp;<a href="#" onclick="expand(\'envvariables\');">(' . count($_ENV) . ')</a></div></h2>' . PHP_EOL;

        $html .= '' . PHP_EOL;
        $html .= '<table cellspacing="0" cellpadding="0" id="envvariables" style="display:none">' . PHP_EOL;
        $no = 1;
        foreach( $_ENV as $name => $value )
        {
            $html .= '<tr>' . PHP_EOL;
            $html .= '  <th class="no" rowspan="2">' . $no . '</th>' . PHP_EOL;
            $html .= '  <td class="key"><span class="value">' . $name . '</span></td>' . PHP_EOL;
            $html .= '</tr>' . PHP_EOL;
            $html .= '<tr>' . PHP_EOL;
            $html .= '  <td class="value"><span class="value">' . $value . '</span></td>' . PHP_EOL;
            $html .= '</tr>' . PHP_EOL;

            $no ++;
        }
        $html .= '</table>' . PHP_EOL;

        // $_SESSION variables

        if ( isset($_SESSION) ){
            $html .= '<h2><div class="value">$_SESSION variables&nbsp;&nbsp;<a href="#" onclick="expand(\'sessionvariables\');">(' . count($_SESSION) . ')</a></div></h2>' . PHP_EOL;

            $html .= '' . PHP_EOL;
            $html .= '<table cellspacing="0" cellpadding="0" id="sessionvariables" style="display:none">' . PHP_EOL;
            $no = 1;
            foreach( $_SESSION as $name => $value )
            {
                $html .= '<tr>' . PHP_EOL;
                $html .= '  <th class="no" rowspan="2">' . $no . '</th>' . PHP_EOL;
                $html .= '  <td class="key"><span class="value">' . $name . '</span></td>' . PHP_EOL;
                $html .= '</tr>' . PHP_EOL;
                $html .= '<tr>' . PHP_EOL;
                $html .= '  <td class="value"><span class="value"><pre>' . print_r(unserialize($value),true) . '</pre></span></td>' . PHP_EOL;
                $html .= '</tr>' . PHP_EOL;

                $no ++;
            }
            $html .= '</table>' . PHP_EOL;
        }

        // output loaded extensions
        $loaded_extensions = get_loaded_extensions();

        $html .= '<h2><div class="value">Loaded Extensions&nbsp;&nbsp;<a href="#" onclick="expand(\'loaded_extensions\');">(' . count($loaded_extensions) . ')</a></div></h2>' . PHP_EOL;

        $html .= '' . PHP_EOL;
        $html .= '<table cellspacing="0" cellpadding="0" id="loaded_extensions" style="display:none">' . PHP_EOL;
        $no = 1;
        foreach( $loaded_extensions as $name => $value )
        {
            $html .= '<tr>' . PHP_EOL;
            $html .= '  <th class="no">' . $no . '</th>' . PHP_EOL;
            $html .= '  <td class="title"><span class="value">' . $value . '</span></td>' . PHP_EOL;
            $html .= '</tr>' . PHP_EOL;

            $no ++;
        }
        $html .= '</table>' . PHP_EOL;



        // output defined constants
        $declared_constants = Charcoal_System::getUserDefinedConstants();

        $html .= '<h2><div class="value">User Declared Constants&nbsp;&nbsp;<a href="#" onclick="expand(\'declared_constants\');">(' . count($declared_constants) . ')</a></div></h2>' . PHP_EOL;

        $html .= '' . PHP_EOL;
        $html .= '<table cellspacing="0" cellpadding="0" id="declared_constants" style="display:none">' . PHP_EOL;
        $no = 1;
        foreach( $declared_constants as $name => $value )
        {
            $html .= '<tr>' . PHP_EOL;
            $html .= '  <th class="no" rowspan="2">' . $no . '</th>' . PHP_EOL;
            $html .= '  <td class="key"><span class="value">' . $name . '</span></td>' . PHP_EOL;
            $html .= '</tr>' . PHP_EOL;
            $html .= '<tr>' . PHP_EOL;
            $html .= '  <td class="value"><span class="value">' . $value . '</span></td>' . PHP_EOL;
            $html .= '</tr>' . PHP_EOL;

            $no ++;
        }
        $html .= '</table>' . PHP_EOL;

        // output defined interfaces
        $declared_interfaces = get_declared_interfaces();
        $interfaces = NULL;
        foreach( $declared_interfaces as $interface )
        {
            $interfaces[] = $interface;
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

        // output defined functions
        $defined_functions = get_defined_functions();
        $functions = NULL;
        foreach( $defined_functions['internal'] as $function )
        {
            $functions[] = '[core]' . $function;
        }
        foreach( $defined_functions['user'] as $function )
        {
            $functions[] = '[user]' . $function;
        }
        sort($functions);

        $html .= '<h2><div class="value">Declared Functions&nbsp;&nbsp;<a href="#" onclick="expand(\'defined_functions\');">(' . count($functions) . ')</a></div></h2>' . PHP_EOL;

        $html .= '' . PHP_EOL;
        $html .= '<table cellspacing="0" cellpadding="0" id="defined_functions" style="display:none">' . PHP_EOL;
        $no = 1;
        foreach( $functions as $function )
        {
            $html .= '<tr>' . PHP_EOL;
            $html .= '  <th class="no">' . $no . '</th>' . PHP_EOL;
            $html .= '  <td class="title"><span class="value">' . $function . '</span></td>' . PHP_EOL;
            $html .= '</tr>' . PHP_EOL;

            $no ++;
        }
        $html .= '</table>' . PHP_EOL;

        // output defined classes
        $declared_klasses = get_declared_classes();
        $klasses = NULL;
        foreach( $declared_klasses as $klass )
        {
            $klasses[] = $klass;
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
        $html .= '<h2><div class="value">Exception Stack</div></h2>' . PHP_EOL;

        $hash = sha1($file . $line);

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

            $e = method_exists( $e, 'getPreviousException' ) ? $e->getPreviousException() : NULL;
            $no ++;
        }
        $html .= '</table>' . PHP_EOL;

        if ( $backtrace === NULL || !is_array($backtrace) ){
            return $html;
        }

        // output call stack
        $html .= '<h2><div class="value">Call Stack</div></h2>' . PHP_EOL;

        $html .= '<table cellspacing="0" cellpadding="0">' . PHP_EOL;
        $call_no = 1;
        foreach( $backtrace as $element ){
            $klass = isset($element['class']) ? $element['class'] : '';
            $func  = isset($element['function']) ? $element['function'] : '';
            $type  = isset($element['type']) ? $element['type'] : '';
            $args  = isset($element['args']) ? $element['args'] : array();
            $file  = isset($element['file']) ? $element['file'] : '';
            $line  = isset($element['line']) ? $element['line'] : 0;

            if ( $type == "::" ){
                $ref_method = new ReflectionMethod( $klass, $func );
                $modifiers = Reflection::getModifierNames( $ref_method->getModifiers() );
                $modifiers = implode(" ",$modifiers);
                $params = $ref_method->getParameters();

                $args_defs = '';
                foreach( $params as $p ){
                    if ( strlen($args_defs) > 0 ){
                        $args_defs .= ',';
                    }
                    if ( $p->isOptional() ){
                        $args_defs .= '[';
                    }
                    if ( $p->isArray() ){
                        $args_defs .= 'array ';
                    }
                    $args_defs .= $p->getClass();
                    if ( $p->isPassedByReference() ){
                        $args_defs .= '&amp;';
                    }
                    $args_defs .= $p->getName();

                    if ( $p->isDefaultValueAvailable() ){
                        $args_defs .= '=' . $p->getDefaultValue();
                    }

                    if ( $p->isOptional() ){
                        $args_defs .= ']';
                    }
                }

                $args_disp  = '<table>';
                $args_disp .= '<tr>';
                $args_disp .= '  <th>No</th>';
                $args_disp .= '  <th>value</th>';
                $args_disp .= '</tr>';
                foreach( $args as $key => $arg ){
                    $args_disp .= '<tr>';
                    $args_disp .= '  <td>' . $key . '</td>';
                    $args_disp .= '  <td>' . Charcoal_System::toString($arg) . '</td>';
                    $args_disp .= '</tr>';
                }
                $args_disp .= '</table>';

                $message = "$modifiers {$klass}{$type}{$func}($args_defs)<br>$args_disp";
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

        return $html;
    }

    /**
     * Render debug trace
     *
     * @param Exception $e
     *
     * @return boolean
     */
    public function render( $e )
    {
        Charcoal_ParamTrait::validateException( 1, $e );

        list( $file, $line ) = Charcoal_System::caller(0);

        $title = 'CharcoalPHP: Exception List';

        if ( $this->clear_buffer->isTrue() ){
            ob_clean();
        }

        echo $this->_output( $e, $title, $file, $line );

        return TRUE;
    }

    /**
     * Output HTML
     *
     */
    public function output( $e )
    {
        Charcoal_ParamTrait::validateException( 1, $e );

        list( $file, $line ) = Charcoal_System::caller(0);

        $title = 'CharcoalPHP: Exception List';

        return $this->_output( $e, $title, $file, $line );
    }

    /**
     * Output HTML
     *
     * @param Exception $e
     * @param string|Charcoal_String $title
     * @param string|Charcoal_String $file
     * @param integer|Charcoal_Integer $line
     *
     * @return string
     */
    private function _output( $e, $title, $file, $line )
    {
        $html  = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        $html .= '<html lang="ja">';
        $html .= '<head>';
        $html .= $this->makeHtmlHead( $title );
        $html .= '</head>';
        $html .= '<body>' . PHP_EOL;
        $html .= $this->makeHtmlBody( $e, $title, $file, $line );
        $html .= '</body>' . PHP_EOL;
        $html .= '</html>' . PHP_EOL;

        return $html;
    }

}


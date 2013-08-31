<?php
/**
* ブラウザに出力するロガークラス（主にデバッグ用）
*
* PHP version 5
*
* @package    loggers
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_ScreenLogger extends Charcoal_BaseLogger implements Charcoal_ILogger
{
	/*
	 * コンストラクタ
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
		parent::configure( $config );
	}

	/*
	 * write header message
	 */
	public function writeHeader()
	{
		$html = <<< HTML_STYLE
<style type="text/css">
  .charcoal {
    font-family: 'Verdana';
  }
  .charcoal table {
    width: 100%;
    border-left: 1px silver solid;
    border-top: 1px silver solid;
    margin-top: 2px;
  }
  .charcoal tr {
    height: 30px;
  }
  .charcoal th {
    border-right: 1px silver solid;
    border-bottom: 1px silver solid;
    background-color: bisque;
    color: coral;
    font-size: 11px;
  }
  .charcoal td {
    border-right: 1px silver solid;
    border-bottom: 1px silver solid;
    background-color: seashell;
    color: darkslategray;
    font-size: 11px;
  }
  .charcoal .value {
    margin: 5px;
  }
  .charcoal .center {
    text-align: center;
  }
/* Source Code */
.source_code {
  font-family: 'Courier New', Arial, Tahoma, Verdana;
  font-size: 10pt;
}

.charcoal td.even {
  background-color: #EEFEEF;
}

.charcoal td.odd {
  background-color: #DDEDDE;
}

.charcoal .level_t { background-color: #EEFEEF; }
.charcoal .level_i { background-color: #DDEDDE; }
.charcoal .level_d { background-color: antiquewhite; }
.charcoal .level_w { background-color: khaki; }
.charcoal .level_e { background-color: lightsalmon; }
.charcoal .level_f { background-color: palevioletred; }
</style>
HTML_STYLE;

		$html .= '<div class="charcoal">' . PHP_EOL;
		$html .= '<table border="0" cellpadding="0" cellspacing="0">' . PHP_EOL;
		$html .= '<tr>' . PHP_EOL;
		$html .= '	<th><div class="value">TimeStamp</div></th>' . PHP_EOL;
		$html .= '	<th><div class="value">Level</div></th>' . PHP_EOL;
		$html .= '	<th><div class="value">Tag</div></th>' . PHP_EOL;
		$html .= '	<th><div class="value">Message</div></th>' . PHP_EOL;
		$html .= '	<th><div class="value">File(Line)</div></th>' . PHP_EOL;
		$html .= '</tr>' . PHP_EOL;

		echo $html;
	}

	/*
	 * write footer message
	 */
	public function writeFooter()
	{
		echo '</table></div>' . PHP_EOL;
	}

	/*
	 * write one message
	 */
	public function writeln( Charcoal_LogMessage $msg )
	{
		$timestamp = date("Y-m-d H:i:s");
		$level     = $msg->getLevel();
		$tag       = $msg->getTag();
		$message   = $msg->getMessage();
		$file      = $msg->getFile();
		$line      = $msg->getLine();

		$level_class_def = array(
				"T" => "level_t",
				"I" => "level_i",
				"D" => "level_d",
				"W" => "level_w",
				"E" => "level_e",
				"F" => "level_f",
			);

		$td_class = isset($level_class_def[us($level)]) ? $level_class_def[us($level)] : NULL;

		$html  = '<tr>' . PHP_EOL;
		$html .= '	<td class="' . $td_class . '"><div class="value center">' . $timestamp . '</div></td>' . PHP_EOL;
		$html .= '	<td class="' . $td_class . '"><div class="value center">' . $level . '</div></td>' . PHP_EOL;
		$html .= '	<td class="' . $td_class . '"><div class="value center">' . $tag . '</div></td>' . PHP_EOL;
		$html .= '	<td class="' . $td_class . '"><div class="value">' . $message . '</div></td>' . PHP_EOL;
		$html .= '	<td class="' . $td_class . '"><div class="value">' . $file . '(' . $line . ')</div></td>' . PHP_EOL;
		$html .= '</tr>' . PHP_EOL;

		echo $html;
	}
}


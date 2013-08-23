<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<META http-equiv='Content-Type' content='text/html; charset=UTF-8'>

<style type="text/css">

* {
  margin: 0px;
  padding: 0px;
}

body {
  margin: 5px;
  padding: 0px;
}

/* タイトル */
.caption {
  font-size: 12pt;
  font-weight: bold;
  color: #666666;
  padding: 2px;
  margin-bottom: 10px;
  border-left: 15px #555555 solid;
  border-bottom: 1px #555555 solid;
}

/* 例外スタック */
/* バックトレース */
.backtrace {
  margin-bottom: 10px;
}

.backtrace_row_level {
  font-size: 9pt;
  width: 20px;
  font-weight: bold;
  text-align: center;
  background-color: silver;
  color: white;
}

.backtrace_row {
  background-color: #FFF68F;
  margin: 5px;
}

.backtrace_row_detail {
  vertical-align: top;
  display: inline;
}



.backtrace_header {
  background-color: #CD950C;
  color: white;
  font-size: 9pt;
  height: 20px;
  width: 100px;
  text-align: center;
  padding: 2px;
}

.backtrace_detail {
  color: darkblue;
  font-size: 9pt;
  padding: 2px;
  text-align: left;
  background-color: transparent; 
}

.backtrace_source {
  width: 1200px;
}


/* ソースコード */
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

</HEAD>
<BODY>

<div class="caption">例外スタック</div>
<div class="backtrace">

<?php
$backtrace = NULL;
$details = NULL;

$level = 0;
while( !Charcoal_ExceptionStack::isEmpty() )
{
	list( $e, $file, $line ) = Charcoal_ExceptionStack::pop();
	$src = new Charcoal_PhpSourceInfo( $file, $line );
	$class_name  = get_class($e);
	$message     = $e->getMessage();

	if ( $e instanceof Charcoal_CharcoalException ){
		$backtrace = $e->getBackTrace();
		$details = $backtrace->build();
	}

	$html =<<< HTML
<div class="backtrace_row">

  <table class="backtrace_row_detail">

    <tr>
      <td rowspan="5" class="backtrace_row_level" width="50">
        [LEVEL]
      </td>
    </tr>

    <tr>
      <th class="backtrace_header">例外クラス名</th>
      <td class="backtrace_detail">[CLASS_NAME]</td>
    </tr>

    <tr>
      <th class="backtrace_header">メッセージ</th>
      <td class="backtrace_detail">[MESSAGE]</td>
    </tr>

    <tr>
      <th class="backtrace_header">ソース</th>
      <td class="backtrace_detail">[FILE]([LINE])</td>
    </tr>

    <tr>
      <td colspan="2" class="backtrace_source">[SOURCE]</td>
    </tr>

  </table>

</div>

HTML;

	$html = str_replace( '[LEVEL]', $level, $html );
	$html = str_replace( '[CLASS_NAME]', $class_name, $html );
	$html = str_replace( '[MESSAGE]', $message, $html );
	$html = str_replace( '[FILE]', $file, $html );
	$html = str_replace( '[LINE]', $line, $html );
	$html = str_replace( '[SOURCE]', $src->toString(), $html );

	echo $html;

	$level ++;
}
echo '</div>' . PHP_EOL;

?>
</div>


<P>

<div class="caption">バックトレース</div>
<div class="backtrace">

<?php
if ( $details && is_array($details) ){
	foreach( $details as $level => $trace )
	{
		$spec = $trace->getSpec();
		$hist = $trace->getHistory();
		$src  = $trace->getSource();

		$file = $src->getFile();
		$line = $src->getLine();

		$html =<<< HTML
<div class="backtrace_row">

  <table class="backtrace_row_detail">

    <tr>
      <td rowspan="5" class="backtrace_row_level" width="50">
        [LEVEL]
      </td>
    </tr>

    <tr>
      <th class="backtrace_header">関数名</th>
      <td class="backtrace_detail">[SPEC]</td>
    </tr>

    <tr>
      <th class="backtrace_header">引数</th>
      <td class="backtrace_detail">[HISTORY]</td>
    </tr>

    <tr>
      <th class="backtrace_header">ソース</th>
      <td class="backtrace_detail">[FILE]([LINE])</td>
    </tr>

    <tr>
      <td colspan="2" class="backtrace_source">[SOURCE]</td>
    </tr>

  </table>

</div>

HTML;

		$html = str_replace( '[LEVEL]', $level, $html );
		$html = str_replace( '[SPEC]', $spec->toString(), $html );
		$html = str_replace( '[HISTORY]', $hist->toString(), $html );
		$html = str_replace( '[FILE]', $file, $html );
		$html = str_replace( '[LINE]', $line, $html );
		$html = str_replace( '[SOURCE]', $src->toString(), $html );

		echo $html;

	}
}
?>

</div>

</body>
</html>

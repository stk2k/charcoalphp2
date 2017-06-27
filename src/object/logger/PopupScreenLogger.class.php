<?php
/**
* ブラウザにポップアップ出力するロガークラス（主にデバッグ用）
*
* PHP version 5
*
* @package    objects.loggers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_PopupScreenLogger extends Charcoal_AbstractLogger implements Charcoal_ILogger
{
    private $_window_open;
    private $_line;
    private $_window_id;

    static $id_master = 0;

    /*
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();

        $this->_window_id = self::$id_master++;

        $this->_window_open = FALSE;

        $this->_line = 0;
    }

    /*
     * ロガーをシャットダウン
     */
    public function terminate()
    {
        $window_id = $this->_window_id;

        echo ('<script language="JavaScript">' . PHP_EOL);
        echo ("w_$window_id.document.writeln('</table></body></html>');" . PHP_EOL);
        echo ("</script>" . PHP_EOL);
    }

    /*
     * 一行出力
     */
    public function writeln( Charcoal_String $level, Charcoal_String $message, Charcoal_String $file, Charcoal_Integer $line )
    {
        $level = $level->getValue();
        $message = $message->getValue();
        $file = $file->getValue();
        $line = $line->getValue();

        $window_id = $this->_window_id;

        if ( $this->_window_open === FALSE ){

            $html_code = Profile::getString( s('HTML_CODE') );

            echo ('<script language="JavaScript">' . PHP_EOL);
            echo ("var w_$window_id = window.open('', $window_id, 'toolbar=no,scrollbars,width=600,height=650');" . PHP_EOL);
            echo ("w_$window_id.document.writeln('<html><head>');" . PHP_EOL);
            echo ("w_$window_id.document.writeln('<META http-equiv=\"Content-Type\" content=\"text/html; charset=$html_code\">');" . PHP_EOL);
            echo ("w_$window_id.document.writeln('<style type=\"text/css\">');" . PHP_EOL);
            echo ("w_$window_id.document.writeln('* { font-family: Courier New; font-size: 8pt; }');" . PHP_EOL);
            echo ("w_$window_id.document.writeln('td.b0 { background-color:#ffffff; }');" . PHP_EOL);
            echo ("w_$window_id.document.writeln('td.b1 { background-color:#CFCFCF; }');" . PHP_EOL);
            echo ("w_$window_id.document.writeln('th { border-bottom: #4169e1 3px solid; }');" . PHP_EOL);
            echo ("w_$window_id.document.writeln('h2 { font-size: 14pt; font-weight: bold; }');" . PHP_EOL);
            echo ("w_$window_id.document.writeln('</style>');" . PHP_EOL);
            echo ("w_$window_id.document.writeln('</head><body>');" . PHP_EOL);
            echo ("w_$window_id.document.writeln('<table border=0 cellspacing=0>');" . PHP_EOL);
            echo ("w_$window_id.document.writeln('<tr><th>Date</th><th>Level</th><th>Message</th><th>File</th><th>Line</th></tr>');" . PHP_EOL);
            echo ("</script>" . PHP_EOL);

            $this->_window_open = TRUE;
        }

        $time = date("y/m/d H:i:s");
        $file = basename($file);

        // 変数展開（PHP5.2.0以前との互換のため）
        $message = System::toString( $message );

        // エンコーディング変換
        $conv = Charcoal_EncodingConverter::fromString( $this->getSandbox(), 'PHP', 'HTML' );
        $message = $conv->convertEncoding( s($message) );

        // ￥を／に変換
        $message = str_replace( '\\', '/', $message );

        // 画面出力
        $message = h($message);
        $clazz = 'b' . ($this->_line % 2);
        $msg  = '<tr>';
        $msg .= '<td class="' . $clazz . '">' . $time . '</td>';
        $msg .= '<td class="' . $clazz . '" style="text-align:center">' . $level . '</td>';
        $msg .= '<td class="' . $clazz . '">' . $message . '</td>';
        $msg .= '<td class="' . $clazz . '">' . $file . '</td>';
        $msg .= '<td class="' . $clazz . '">' . $line . '</td>';
        $msg .= '</tr>';

        echo '<script language="JavaScript">' . PHP_EOL;
        echo "w_$window_id.document.writeln('$msg');" . PHP_EOL;
        echo '</script>' . PHP_EOL;

        $this->_line ++;
    }

    /**
     *  String expression of this object
     *
     * @return string
     */
    public function toString()
    {
        return $this->getLoggerName();
    }
}


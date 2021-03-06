<?php
/**
* Csvファイルに出力するロガークラス
*
* PHP version 5
*
* @package    objects.loggers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_CsvFileLogger extends Charcoal_FileLogger implements Charcoal_ILogger
{
    const DQ    = '"';
    const CR    = "\r";
    const LF    = "\n";
    const CRLF  = "\r\n";

    private $_field_order;
    private $_delimiter;
    private $_double_quoted;
    private $_eol_code;

    /*
     *    コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
    }

    /*
     * デストラクタ
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Initialize instance
     *
     * @param array $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure($config);
        
        $config = new Charcoal_HashMap($config);

        $this->_field_order    = $config->getArray( 'field_order', array() );
        $this->_delimiter      = $config->getInteger( 'delimiter', 0 );
        $this->_double_quoted  = $config->getBoolean( 'double_quoted', FALSE );
        $this->_eol_code       = $config->getInteger( 'eol_code', 0 );
    }

    /*
     * フィールドをエスケープしてダブルクォーテーションで囲む
     */
    private function EscapeQuote( Charcoal_String $str )
    {
        $str = us($str);

        $str = str_replace( ",", "_", $str );
        $str = str_replace( self::DQ, "'", $str );

        if ( $this->_double_quoted ){
            $str = self::DQ . $str . self::DQ;
        }

        return $str;
    }

    /*
     * 一行出力
     */
    public function writeln( Charcoal_LogMessage $msg )
    {
        $req_path= $this->getSandbox()->getEnvironment()->get( '%REQUEST_PATH%' );
        $req_id  = $this->getSandbox()->getEnvironment()->get( '%REQUEST_ID%' );
        $ip      = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '-';
        $level   = $msg->getLevel();
        $message = $msg->getMessage();
        $file    = $msg->getFile();
        $line    = $msg->getLine();

        $time = date("y/m/d H:i:s");
        $file = basename($file);

        // エンコーディング変換
        $message = $this->convertEncoding( s($message) );

        // 接続処理
        $this->open();

        // 区切り文字
        $delimiter = ",";
        if ( ui($this->_delimiter) == 1 ){
            $delimiter = "\t";
        }

        // 改行コード
        $eol = self::CRLF;
        if ( ui($this->_eol_code) == 1 ){
            $eol = self::CR;
        }
        if ( ui($this->_eol_code) == 2 ){
            $eol = self::LF;
        }

        // ファイル書き込み
        $orders = uv($this->_field_order);
        $out = "";

        foreach( $orders as $field ){
            $field = strtoupper(trim($field));
            if ( strlen($out) > 0 ){
                $out .= $delimiter;
            }
            switch( $field )
            {
                case "%REQUEST_PATH%":
                    $out .= $this->EscapeQuote(s($req_path));
                    break;
                case "%IP%":
                    $out .= $this->EscapeQuote(s($ip));
                    break;
                case "%REQUEST_ID%":
                    $out .= $this->EscapeQuote(s($req_id));
                    break;
                case "%TIME%":
                    $out .= $this->EscapeQuote(s($time));
                    break;
                case "%LEVEL%":
                    $out .= $this->EscapeQuote(s($level));
                    break;
                case "%MESSAGE%":
                    $out .= $this->EscapeQuote(s($message));
                    break;
                case "%FILE%":
                    $out .= $this->EscapeQuote(s($file));
                    break;
                case "%LINE%":
                    $out .= $this->EscapeQuote(s($line));
                    break;
                default:
                    break;
            }
        }

        $out .= $eol;

        $this->write( s($out) );
    }
}


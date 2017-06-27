<?php
/**
* コンソールに出力するロガークラス（主にデバッグ用）
*
* PHP version 5
*
* @package    objects.loggers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ConsoleLogger extends Charcoal_AbstractLogger implements Charcoal_ILogger
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
     * @param array $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );
    }

    /*
     * ロガーをシャットダウン
     */
    public function terminate()
    {
    }

    /*
     * 一行出力
     */
    public function writeln( Charcoal_LogMessage $msg )
    {
        // フォーマット
        $out = parent::formatMessage( $msg )  . PHP_EOL;

        // エンコーディング変換
        $conv = Charcoal_EncodingConverter::fromString( $this->getSandbox(), 'PHP', 'CLI' );
        $out = $conv->convert( $out );

        // 画面出力
        echo $out;
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


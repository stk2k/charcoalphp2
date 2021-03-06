<?php
/**
* HTMLファイルに出力するロガークラス
*
* PHP version 5
*
* @package    objects.loggers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_HtmlFileLogger extends Charcoal_FileLogger implements Charcoal_ILogger
{
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
        parent::configure( $config );
    }

    /*
     * 一行出力
     */
    public function writeln( Charcoal_LogMessage $message )
    {
        // 接続処理
        $this->open();

        // ファイル書き込み
        parent::write ( $message );
    }

}


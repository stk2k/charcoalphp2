<?php
/**
* イベント処理中断イベントクラス
*
* PHP version 5
*
* @package    objects.events
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_AbortEvent extends Charcoal_SystemEvent 
{
    private $abort_type;
    private $exit_code;

    /**
     *   Consructor
     *
     * @param integer $abort_type
     * @param integer $exit_code
     */
    public function __construct( $abort_type = NULL, $exit_code = NULL )
    {
        parent::__construct();
    
        $this->abort_type = $abort_type ? $abort_type : Charcoal_Event::ABORT_TYPE_AFTER_THIS_LOOP;
        $this->exit_code = $exit_code ? $exit_code : Charcoal_Event::EXIT_CODE_ABORT;
    }
    
    /**
     *    アボートタイプを取得
     *
     * @return integer
     */
    public function getAbortType()
    {
        return $this->abort_type;
    }

    /**
     *    結果コードを取得
     *
     * @return integer
     */
    public function getExitCode()
    {
        return $this->exit_code;
    }
}


<?php
/**
* temporary flag switcher for error reporting
*
* PHP version 5
*
* @package    class.util
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_ErrorReportingSwitcher
{
    private $old_flags;

    /*
     *    コンストラクタ
     */
    public function __construct( $flag_add, $flag_remove = 0 )
    {
        $this->old_flags = error_reporting();
        $new_flags = $this->old_flags;
        if ( $flag_add != 0 ){
            $new_flags |= $flag_add;
        }
        if ( $flag_remove != 0 ){
            $new_flags &= ~$flag_remove;
        }
        error_reporting($new_flags);
    }

    /*
     *    デストラクタ
     */
    public function __destruct()
    {
        if ( $this->old_flags ){
            error_reporting( $this->old_flags );
        }
    }
}

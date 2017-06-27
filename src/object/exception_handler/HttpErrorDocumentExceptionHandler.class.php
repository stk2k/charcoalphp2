<?php
/**
* HTTP Error Document Exception Handler
*
* PHP version 5
*
* @package    objects.exception_handlers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_HttpErrorDocumentExceptionHandler extends Charcoal_AbstractExceptionHandler
{
    private $_show_exception_stack;

    /**
     * Initialize instance
     *
     * @param array $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );
    
        $config = new Charcoal_HashMap($config);

        $this->_show_exception_stack = $config->getBoolean( 'show_exception_stack', TRUE );
    }

    /*
     *    HTTPエラードキュメントを表示
     */
    public static function showHttpErrorDocument( $status_code )
    {
//        Charcoal_ParamTrait::validateInteger( 1, $status_code );

        $status_code = ui($status_code);

        // HTML
        $html_file = $status_code . '.html';

        // アプリケーション以下のerror_docを検索
        $html_file_path = Charcoal_ResourceLocator::getApplicationPath( 'error_doc', $html_file );
        if ( !is_file($html_file_path) ){
//            log_info( 'system,debug,error',"エラードキュメント($html_file_path)は存在しません。", 'framework');

            // プロジェクト以下のerror_docを検索
            $html_file_path = Charcoal_ResourceLocator::getProjectPath( 'error_doc' , $html_file );
            if ( !is_file($html_file_path) ){
//                log_debug( 'system,debug,error',"エラードキュメント($html_file_path)は存在しません。", 'framework');

                // フレームワーク以下のerror_docを検索
                $html_file_path = Charcoal_ResourceLocator::getFrameworkPath( 'error_doc', $html_file );
                if ( !is_file($html_file_path) ){
//                    log_warning( 'system,debug,error',"エラードキュメント($html_file_path)は存在しません。", 'framework');
                }
            }
        }

        // 読み込みと表示
        if ( is_file($html_file_path) ){
            readfile( $html_file_path );
            print "<br>";
        }
    }

    /**
     * execute exception handlers
     *
     * @param Exception $e     exception to handle
     *
     * @return boolean        TRUE means the exception is handled, otherwise FALSE
     */
    public function handleException( $e )
    {
        Charcoal_ParamTrait::validateException( 1, $e );

        if ( $e instanceof Charcoal_HttpStatusException )
        {
            $status_code = $e->getStatusCode();

            // Show HTTP error document
            self::showHttpErrorDocument( $status_code );

            log_warning( 'system,error', 'exception', "http_exception: status_code=$status_code");

            return TRUE;
        }

        return FALSE;
    }

}


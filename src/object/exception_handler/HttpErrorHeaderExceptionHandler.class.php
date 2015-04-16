<?php
/**
* HTTP Error Header Exception Handler
*
* PHP version 5
*
* @package    objects.exception_handlers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_HttpErrorHeaderExceptionHandler extends Charcoal_AbstractExceptionHandler
{
	private $http_version;

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		$this->http_version = $config->getString( 'http_version', '1.0' );
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

			// ヘッダ文字列
			$status_message_file = Charcoal_ResourceLocator::getFrameworkPath( 'preset' , 'status_messages.ini' );
			if ( !is_file($status_message_file) ){
	//			log_warning( 'system,debug,error', 'framework',"ステータスメッセージファイル($status_message_file)が存在しません。");
			}
			$status_messages = parse_ini_file($status_message_file,FALSE);
			if ( FALSE === $status_messages ){
	//			log_warning( 'system,debug,error', 'framework',"ステータスメッセージファイル($status_message_file)の読み込みに失敗しました。");
			}
			$header_msg = isset($status_messages[$status_code]) ? $status_messages[$status_code] : '';

			// HTTPバージョン文字列
			$http_ver = $this->http_version;

			// ヘッダ出力
			header( "HTTP/{$http_ver} {$status_code} {$header_msg}", true, $status_code );

			log_error( 'system,debug,error', "HTTP/1.0 $status_code $header_msg", 'framework');

			return TRUE;
		}

		return FALSE;
	}

}


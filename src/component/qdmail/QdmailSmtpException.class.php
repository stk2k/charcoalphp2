<?php
/**
* SMTP送信例外
*
* PHP version 5
*
* @package    component.qdmail
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_QdmailSmtpException extends Charcoal_RuntimeException
{
	private $stack;
	private $status_code;

	/***
	 *	コンストラクタ
	 **/
	public function __construct( Qdsmtp $qdsmtp, Exception $previous = NULL )
	{
		$this->stack = $qdsmtp->errorStatment();

		foreach( $this->stack as $item ){
			if ( strpos( $item, "Error :status555" ) !== FALSE ){
				$this->status_code = Charcoal_EnumSmtpStatusCode::SYNTAX_ERROR;	// 555
				break;
			}
			elseif ( strpos( $item, "Error :status503" ) !== FALSE ){
				$this->status_code = Charcoal_EnumSmtpStatusCode::BAD_SEQUENCE_OF_COMMANDS;	// 503
				break;
			}
		}

		$msg = $this->toString();
		if ( $previous ) parent::__construct( s($msg), $previous ); else parent::__construct( s($msg) );
	}

	/***
	 *	エラースタック
	 **/
	public function getStack()
	{
		return $this->stack;
	}

	/***
	 *	SMTPステータスコード
	 **/
	public function getStatusCode()
	{
		return $this->status_code;
	}

	/*
	 *	文字列化
	 */
	public function toString()
	{
		return "Qdsmtp error: Status=" . $this->status_code;
	}
}



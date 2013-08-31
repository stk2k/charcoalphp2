<?php
/**
* メール送信例外
*
* PHP version 5
*
* @package    components.mail
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_QdmailException extends Charcoal_RuntimeException
{
	private $stack;

	/***
	 *	コンストラクタ
	 **/
	public function __construct( Qdmail $qdmail, Exception $previous = NULL )
	{
		$this->stack = $qdmail->errorStatment();

		$msg = "Qdmail error:" . print_r($this->stack,true);
		if ( $previous ) parent::__construct( s($msg), $previous ); else parent::__construct( s($msg) );

		foreach( $this->stack as $item ){
			log_error( "mail", $item );
		}
	}

	/***
	 *	エラースタック
	 **/
	public function getStack()
	{
		return $this->stack;
	}

}



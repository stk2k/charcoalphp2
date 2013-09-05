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

class Charcoal_QdmailAddress
{
	private $address;
	private $label;

	/***
	 *	コンストラクタ
	 **/
	public function __construct( Charcoal_String $address, Charcoal_String $label = NULL )
	{
		$this->address = $address;
		$this->label = $label;

		$valid = Charcoal_MailUtil::validateAddress( s($address) );
		if ( !$valid ){
			_throw( new Charcoal_InvalidMailAddressException( $address ) );
		}
	}

	/***
	 *	アドレス
	 **/
	public function getAddress()
	{
		return $this->address;
	}

	/***
	 *	アドレスの表示名が指定されているか
	 **/
	public function hasLabel()
	{
		return $this->label != NULL;
	}

	/***
	 *	アドレスの表示名
	 **/
	public function getLabel()
	{
		return $this->label;
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return $this->label . '<' . $this->address . '>';
	}
}



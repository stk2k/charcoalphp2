<?php
/**
* メールユーティリティクラス
*
* PHP version 5
*
* @package    class.util
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_MailUtil
{
	const REGEX_EMAIL = "/^[_a-z0-9-_]+(\.[_a-z0-9-_]+)*@[a-z0-9-_]+([\.][a-z0-9-_]+)+$/i";

	/**
	 *	Check sting if valid mail address
	 */
	static function validateAddress( Charcoal_String $address )
	{
		$address = us($address);

		$is_mail = (preg_match(self::REGEX_EMAIL, $address) === 1);

		return $is_mail;
	}
}



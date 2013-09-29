<?php
/**
* Form Test Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class QdmailTestTask extends Charcoal_TestTask
{
	/**
	 * setup test
	 */
	public function setUp()
	{

	}

	/**
	 * clean up test
	 */
	public function cleanUp()
	{
	}

	/**
	 * execute tests
	 */
	public function test( $action, $context )
	{
		$request   = $context->getRequest();

		$action = us($action);

		// Qdmail
		$qdmail = $context->getComponent( s('qdmail@:qdmail') );

		$config = new Charcoal_Config();

		$config->set( s('qdsmtp.host'), 'localhost' );
		$config->set( s('qdsmtp.port'), '25' );
		$config->set( s('qdsmtp.from'), 'stk2k@sazysoft.com' );
		$config->set( s('qdsmtp.protocol'), 'SMTP' );
		$config->set( s('qdsmtp.user'), '' );
		$config->set( s('qdsmtp.pass'), '' );

		$qdmail->configure( $config );

		switch( $action ){
		// Send mail
		case "send_mail":
			$to      = $request->get( s("to") );
			$from    = "stk2k@sazysoft.com";
			$subject = "test";
			$body    = "test!!!";
			echo "to:" . $to . eol();
			$qdmail->sendMail( $from, $to, $subject, $body );
			break;
		}
	}

}

return __FILE__;
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
		$qdmail = $context->getComponent( 'qdmail@:qdmail' );

		$config = new Charcoal_Config();

		$config->set( 'qdsmtp.host', 'localhost' );
		$config->set( 'qdsmtp.port', '25' );
		$config->set( 'qdsmtp.from', 'stk2k@sazysoft.com' );
		$config->set( 'qdsmtp.protocol', 'SMTP' );
		$config->set( 'qdsmtp.user', '' );
		$config->set( 'qdsmtp.pass', '' );

		$qdmail->configure( $config );

		switch( $action ){
		// Send mail
		case "send_mail":
			$to      = $request->get( "to" );
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
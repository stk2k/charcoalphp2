<?php
/**
* タスク
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

abstract class Charcoal_TestTask extends Charcoal_Task
{
	private $tests;
	private $asserts;
	private $action;
	private $expected_exception;

	/**
	 * Configure component
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config )
	{
		parent::configure( $config );
	}

	/**
	 * check if action will be processed
	 */
	public abstract function isValidAction( Charcoal_String $action );

	/**
	 * セットアップ
	 */
	public abstract function setUp( Charcoal_String $action );

	/**
	 * クリーンアップ
	 */
	public abstract function cleanUp( Charcoal_String $action );

	/**
	 * テスト
	 */
	public abstract function test( Charcoal_String $action, Charcoal_IEventContext $context );

	/**
	 * Set expected exception class name
	 */
	public function setExpectedException( Charcoal_String $expected_exception )
	{
		$this->expected_exception = us($expected_exception);
	}

	/**
	 * アサートメッセージ
	 */
	public function message2( $result, $value1_title, $value2_title, $value1, $value2 )
	{
		list( $file, $line ) = Charcoal_System::caller(2);

		print "[ASSERT] $result" . eol();
		print "  $value1_title: " . $value1 . eol();
		print "  $value2_title: " . Charcoal_System::toString($value2,TRUE) . eol();
		print "  $file($line)" . eol();

		$this->asserts ++;
	}
	public function messageExpectedActual( $result, $expected, $actual )
	{
		$this->message2( $result, "Expected", "Actual", $expected, $actual );
	}
	public function messageNeedleHaystack( $result, $needle, $haystack )
	{
		$this->message2( $result, "Needle", "Haystack", $expected, $actual );
	}

	/**
	 * assert
	 */
	public function assertNull( $actual )
	{
		$this->tests ++;
		if ( $actual !== NULL ){
			$this->messageExpectedActual( "Null", "=== NULL", $actual );
		}
	}
	public function assertNotNull( $actual )
	{
		$this->tests ++;
		if ( $actual === NULL ){
			$this->messageExpectedActual( "Not Null", "=== NULL", $actual );
		}
	}
	public function assertEmpty( $actual )
	{
		$this->tests ++;
		if ( !empty($actual) ){
			$this->messageExpectedActual( "Empty", "''", $actual );
		}
	}
	public function assertNotEmpty( $actual )
	{
		$this->tests ++;
		if ( empty($actual) ){
			$this->messageExpectedActual( "Not Empty", "''", $actual );
		}
	}
	public function assertEquals( $expected, $actual )
	{
		$this->tests ++;
		if ( $expected != $actual ){
			$expected = Charcoal_System::toString($expected,TRUE);
			$this->messageExpectedActual( "Not Equal", "== $expected", $actual );
		}
	}
	public function assertNotEquals( $expected, $actual )
	{
		$this->tests ++;
		if ( $expected == $actual ){
			$expected = Charcoal_System::toString($expected,TRUE);
			$this->messageExpectedActual( "Equal", "!= $expected", $actual );
		}
	}
	public function assertSame( $expected, $actual )
	{
		$this->tests ++;
		if ( $expected !== $actual ){
			$expected = Charcoal_System::toString($expected,TRUE);
			$this->messageExpectedActual( "Not Same", "=== $expected", $actual );
		}
	}
	public function assertNotSame( $expected, $actual )
	{
		$this->tests ++;
		if ( $expected === $actual ){
			$expected = Charcoal_System::toString($expected,TRUE);
			$this->messageExpectedActual( "Same", "!== $expected", $actual );
		}
	}
	public function assertFalse( $actual )
	{
		$this->tests ++;
		if ( $actual !== FALSE ){
			$this->messageExpectedActual( "Not FALSE", "=== FALSE", $actual );
		}
	}
	public function assertTrue( $actual )
	{
		$this->tests ++;
		if ( $actual !== TRUE ){
			$this->messageExpectedActual( "Not TRUE", "=== TRUE", $actual );
		}
	}
	public function assertCotains( $needle, $haystack )
	{
		$this->tests ++;
		if ( $actual !== TRUE ){
			$this->messageNeedleHaystack( "Not Contains", $needle, $haystack );
		}
	}

	/**
	 * Process events
	 *
	 * @param Charcoal_IEventContext $context   event context
	 */
	public function processEvent( Charcoal_IEventContext $context )
	{
		$request   = $context->getRequest();
		$response  = $context->getResponse();
		$sequence  = $context->getSequence();
		$procedure = $context->getProcedure();

		// パラメータを取得
		$actions       = $request->getString( s("actions") );

		$action_list = $actions->split( s(',') );

		// アクションに対するテストが記述されているか確認する
		$total_actions = 0;
		foreach( $action_list as $action ){
			if ( $this->isValidAction(s($action)) )	$total_actions ++;
		}
		if ( $total_actions === 0 ){
			return b(TRUE);
		}

		// テスト実行
		$this->tests = 0;
		$this->asserts = 0;
		$errors = 0;

		print eol();
		print "==========================================" . eol();
		print "CharcoalPHP Test Runner" . eol();
		print "   Framework Version:" . Charcoal_Framework::getVersion() . eol();
		print "==========================================" . eol();

		print "TargetTask:" . $this->getObjectName() . eol();
		print "Test started(total=$total_actions)." . eol();

		foreach( $action_list as $action ){
			$this->action = $action = trim( $action );

			if ( !$this->isValidAction(s($action)) )	continue;

			print "-------------------------------------" . eol();
			print "$action" . eol();

			try{
				$this->setUp( s($action) );
			}
			catch( Exception $e ){
				print "Test execution failed while setup:" . $e . eol();
				return b(true);
			}

			try{
				$this->test( s($action), $context );
			}
			catch( Exception $e ){
				print "[Info]Caught exception:" . get_class($e) . eol();
				if ( $this->expected_exception ){
					if ( $this->expected_exception != get_class($e) ){
						$expected = $this->expected_exception;
						$actual = get_class($e);
						$this->message2( $result, "Expected", "Actual", $expected, $actual );
					}
				}
				else{
					print "[Warning]Test execution failed while test:" . $e . eol();
				}
			}

			try{
				$this->cleanUp( s($action) );
			}
			catch( Exception $e ){
				print "Test execution failed while clean up:" . $e . eol();
				return b(true);
			}

		}

		// 終了メッセージ
		print "-------------------------------------" . eol();
		if ( $this->tests > 0 ){
			print "Tests complete!" . eol();
			print "Tests: {$this->tests} Assertions: {$this->asserts} Errors: $errors" . eol();
		}
		else{
			print "No tests were processed." . eol();
		}

		return b(TRUE);
	}
}

return __FILE__;
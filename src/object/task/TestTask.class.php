<?php
/**
* タスク
*
* PHP version 5
*
* @package    objects.tasks
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/
abstract class Charcoal_TestTask extends Charcoal_Task
{
    private $tests;
    private $asserts;
    private $failures;
    private $section;
    private $action;
    private $expected_exception;
    private $context;



    /**
     * Initialize instance
     *
     * @param array $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );

        $this->setPostActions( array('remove_event') );


        if ( $this->getSandbox()->isDebug() )
        {
            log_debug( "debug", "Task[$this] post actions: " . $this->getPostActions(), self::TAG );
        }
    }

    /**
     * check if action will be processed
     *
     * @param Charcoal_String|string $action
     */
    public abstract function isValidAction( $action );

    /**
     * setup tests
     *
     * @param Charcoal_String|string $action
     * @param Charcoal_IEventContext $context
     */
    public abstract function setUp( $action, $context );

    /**
     * clean up tests
     *
     * @param Charcoal_String|string $action
     * @param Charcoal_IEventContext $context
     */
    public abstract function cleanUp( $action, $context );

    /**
     * do tests
     *
     * @param Charcoal_String|string $action
     * @param Charcoal_IEventContext $context
     */
    public abstract function test( $action, $context );

    /**
     * Set expected exception class name
     *
     * @param string $expected_exception
     */
    public function setExpectedException( $expected_exception )
    {
        $this->expected_exception = $expected_exception;
    }

    /**
     * output messages of value1 and value2
     *
     * @param Charcoal_String|string $result
     * @param Charcoal_String|string $value1_title
     * @param Charcoal_String|string $value2_title
     * @param mixed $value1
     * @param mixed $value2
     */
    public function message2( $result, $value1_title, $value2_title, $value1, $value2 )
    {
        list( $file, $line ) = Charcoal_System::caller(2);

        echo "[ASSERT] $result" . PHP_EOL;
        echo "  $value1_title: " . $value1 . PHP_EOL;
        echo "  $value2_title: " . Charcoal_System::toString($value2,TRUE) . PHP_EOL;
        echo "  $file($line)" . PHP_EOL;

        $this->failures ++;

        $event_args = array( $this->section, $this->action, false );
        /** @var Charcoal_IEventContext $context */
        $context = $this->context;
        /** @var Charcoal_IEvent $event */
        $event = $context->createEvent( 'test_result', $event_args );
        $context->pushEvent( $event );
    }
    public function messageExpectedActual( $result, $expected, $actual )
    {
        $this->message2( $result, "Expected", "Actual", $expected, $actual );
    }
    public function messageNeedleHaystack( $result, $needle, $haystack )
    {
        $this->message2( $result, "Needle", "Haystack", $needle, $haystack );
    }

    /**
     * test success
     */
    public function success()
    {
        $event_args = array( $this->section, $this->action, true );
        /** @var Charcoal_IEventContext $context */
        $context = $this->context;
        /** @var Charcoal_IEvent $event */
        $event = $context->createEvent( 'test_result', $event_args );
        $context->pushEvent( $event );
    }

    /**
     * assert if NULL
     *
     * @param mixed $actual
     * @param boolean $verbose
     */
    public function assertNull( $actual, $verbose = false )
    {
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $verbose ){
            echo __METHOD__ . "($actual_s)" . PHP_EOL;
        }
        $this->asserts ++;
        if ( $actual !== NULL ){
            $this->messageExpectedActual( "Null", "=== NULL", $actual_s );
        }
        else{
            $this->success();
        }
    }

    /**
     * assert if NOT NULL
     *
     * @param mixed $actual
     * @param boolean $verbose
     */
    public function assertNotNull( $actual, $verbose = false )
    {
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $verbose ){
            echo __METHOD__ . "($actual_s)" . PHP_EOL;
        }
        $this->asserts ++;
        if ( $actual === NULL ){
            $this->messageExpectedActual( "Not Null", "=== NULL", $actual_s );
        }
        else{
            $this->success();
        }
    }

    /**
     * assert if empty
     *
     * @param mixed $actual
     * @param boolean $verbose
     */
    public function assertEmpty( $actual, $verbose = false )
    {
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $verbose ){
            echo __METHOD__ . "($actual_s)" . PHP_EOL;
        }
        $this->asserts ++;
        if ( !empty($actual) ){
            $this->messageExpectedActual( "Empty", "''", $actual_s );
        }
        else{
            $this->success();
        }
    }

    /**
     * assert if NOT empty
     *
     * @param mixed $actual
     * @param boolean $verbose
     */
    public function assertNotEmpty( $actual, $verbose = false )
    {
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $verbose ){
            echo __METHOD__ . "($actual_s)" . PHP_EOL;
        }
        $this->asserts ++;
        if ( empty($actual) ){
            $this->messageExpectedActual( "Not Empty", "''", $actual_s );
        }
        else{
            $this->success();
        }
    }

    /**
     * assert if equal
     *
     * @param mixed $expected
     * @param mixed $actual
     * @param boolean $verbose
     */
    public function assertEquals( $expected, $actual, $verbose = false )
    {
        $expected_s = Charcoal_System::toString($expected,TRUE);
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $verbose ){
            echo __METHOD__ . "($expected_s, $actual_s)" . PHP_EOL;
        }
        $this->asserts ++;
        if ( $expected != $actual ){
            $this->messageExpectedActual( "Not Equal", "== $expected_s", $actual_s );
        }
        else{
            $this->success();
        }
    }

    /**
     * assert if NOT equal
     *
     * @param mixed $expected
     * @param mixed $actual
     * @param boolean $verbose
     */
    public function assertNotEquals( $expected, $actual, $verbose = false )
    {
        $expected_s = Charcoal_System::toString($expected,TRUE);
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $verbose ){
            echo __METHOD__ . "($expected_s, $actual_s)" . PHP_EOL;
        }
        $this->asserts ++;
        if ( $expected == $actual ){
            //$expected = Charcoal_System::toString($expected,TRUE);
            $this->messageExpectedActual( "Equal", "!= $expected_s", $actual_s );
        }
        else{
            $this->success();
        }
    }

    /**
     * assert if same
     *
     * @param mixed $expected
     * @param mixed $actual
     * @param boolean $verbose
     */
    public function assertSame( $expected, $actual, $verbose = false )
    {
        $expected_s = Charcoal_System::toString($expected,TRUE);
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $verbose ){
            echo __METHOD__ . "($expected_s, $actual_s)" . PHP_EOL;
        }
        $this->asserts ++;
        if ( $expected !== $actual ){
            //$expected = Charcoal_System::toString($expected,TRUE);
            $this->messageExpectedActual( "Not Same", "=== $expected_s", $actual_s );
        }
        else{
            $this->success();
        }
    }

    /**
     * assert if NOT same
     *
     * @param mixed $expected
     * @param mixed $actual
     * @param boolean $verbose
     */
    public function assertNotSame( $expected, $actual, $verbose = false )
    {
        $expected_s = Charcoal_System::toString($expected,TRUE);
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $verbose ){
            echo __METHOD__ . "($expected_s, $actual_s)" . PHP_EOL;
        }
        $this->asserts ++;
        if ( $expected === $actual ){
            //$expected = Charcoal_System::toString($expected,TRUE);
            $this->messageExpectedActual( "Same", "!== $expected_s", $actual_s );
        }
        else{
            $this->success();
        }
    }

    /**
     * assert if FALSE
     *
     * @param mixed $actual
     * @param boolean $verbose
     */
    public function assertFalse( $actual, $verbose = false )
    {
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $verbose ){
            echo __METHOD__ . "($actual_s)" . PHP_EOL;
        }
        $this->asserts ++;
        if ( $actual !== FALSE ){
            $this->messageExpectedActual( "Not FALSE", "=== FALSE", $actual_s );
        }
        else{
            $this->success();
        }
    }

    /**
     * assert if TRUE
     *
     * @param mixed $actual
     * @param boolean $verbose
     */
    public function assertTrue( $actual, $verbose = false )
    {
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $verbose ){
            echo __METHOD__ . "($actual_s)" . PHP_EOL;
        }
        $this->asserts ++;
        if ( $actual !== TRUE ){
            $this->messageExpectedActual( "Not TRUE", "=== TRUE", $actual_s );
        }
        else{
            $this->success();
        }
    }

    /**
     * assert if TRUE
     *
     * @param mixed $needle
     * @param array $haystack
     * @param boolean $verbose
     */
    public function assertCotains( $needle, $haystack, $verbose = false )
    {
        $needle_s = Charcoal_System::toString($needle,TRUE);
        $haystack_s = Charcoal_System::toString($haystack,TRUE);
        if ( $verbose ){
            echo __METHOD__ . "($needle_s, $haystack_s)" . PHP_EOL;
        }
        $this->asserts ++;
        if ( in_array($needle, $haystack) ){
            $this->messageNeedleHaystack( "Not Contains", $needle_s, $haystack_s );
        }
        else{
            $this->success();
        }
    }

    /**
     * assert if greater than
     *
     * @param mixed $expected
     * @param mixed $actual
     * @param boolean $verbose
     */
    public function assertGreaterThan( $expected, $actual, $verbose = false )
    {
        $expected_s = Charcoal_System::toString($expected,TRUE);
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $verbose ){
            echo __METHOD__ . "($expected_s, $actual_s)" . PHP_EOL;
        }
        $this->asserts ++;
        if ( $expected >= $actual ){
            $this->messageExpectedActual( "Less than or equal", ">= $expected_s", $actual_s );
        }
        else{
            $this->success();
        }
    }

    /**
     * assert if greater than or equal
     *
     * @param mixed $expected
     * @param mixed $actual
     * @param boolean $verbose
     */
    public function assertGreaterThanOrEqual( $expected, $actual, $verbose = false )
    {
        $expected_s = Charcoal_System::toString($expected,TRUE);
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $verbose ){
            echo __METHOD__ . "($expected_s, $actual_s)" . PHP_EOL;
        }
        $this->asserts ++;
        if ( $expected > $actual ){
            $this->messageExpectedActual( "Less than", "> $expected_s", $actual_s );
        }
        else{
            $this->success();
        }
    }

    /**
     * assert if less than
     *
     * @param mixed $expected
     * @param mixed $actual
     * @param boolean $verbose
     */
    public function assertLessThan( $expected, $actual, $verbose = false )
    {
        $expected_s = Charcoal_System::toString($expected,TRUE);
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $verbose ){
            echo __METHOD__ . "($expected_s, $actual_s)" . PHP_EOL;
        }
        $this->asserts ++;
        if ( $expected <= $actual ){
            $this->messageExpectedActual( "Greater than or equal", "<= $expected_s", $actual_s );
        }
        else{
            $this->success();
        }
    }

    /**
     * assert if less than or equal
     *
     * @param mixed $expected
     * @param mixed $actual
     * @param boolean $verbose
     */
    public function assertLessThanOrEqual( $expected, $actual, $verbose = false )
    {
        $expected_s = Charcoal_System::toString($expected,TRUE);
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $verbose ){
            echo __METHOD__ . "($expected_s, $actual_s)" . PHP_EOL;
        }
        $this->asserts ++;
        if ( $expected < $actual ){
            $this->messageExpectedActual( "Less than", "< $expected_s", $actual_s );
        }
        else{
            $this->success();
        }
    }

    /**
     * Process events
     *
     * @param Charcoal_IEventContext $context   event context
     *
     * @return Charcoal_Boolean|bool
     */
    public function processEvent( $context )
    {
        $this->context = $context;

        /** @var Charcoal_TestEvent $event */
        $event   = $context->getEvent();

        $is_debug = $context->isDebug();

        // パラメータを取得
        $section       = $event->getSection();
        $target        = $event->getTarget();
        $actions       = $event->getActions();

        $this->section = $section;

        if ( $is_debug ) log_debug( "debug,event", "event section: $section" );
        if ( $is_debug ) log_debug( "debug,event", "event target: $target" );
        if ( $is_debug ) log_debug( "debug,event", "event actions: $actions" );

        if ( $is_debug ) log_debug( "debug,event", "this object path: " . $this->getObjectPath() );

        if ( $target != $this->getObjectPath() ){
            if ( $is_debug ) log_debug( "debug,event", "not target: " . $event );
            return FALSE;
        }
        if ( $is_debug ) log_debug( "debug,event", "target: " . $event );

        $actions = explode( ',', $actions );

        // アクションに対するテストが記述されているか確認する
        $total_actions = 0;
        if ( $actions ){
            foreach( $actions as $action ){
                $action = trim($action);
                if ( strlen($action) === 0 )    continue;

                if ( $this->isValidAction( $action ) )    $total_actions ++;
            }
        }
        if ( $total_actions === 0 ){
            return TRUE;
        }

        // テスト実行
        $this->tests = 0;
        $this->asserts = 0;
        $this->failures = 0;

        echo PHP_EOL . "===================================================" . PHP_EOL;
        echo "Section[$section](total actions:$total_actions)" . PHP_EOL . PHP_EOL;

        foreach( $actions as $action ){
            $action = trim( $action );
            if ( strlen($action) === 0 )    continue;

            $this->action = $action;

            if ( !$this->isValidAction( $action ) )    continue;

            //echo "-------------------------------------" . PHP_EOL;
            echo "Doing action: [$action] ..." . PHP_EOL;

            try{
                $this->setUp( $action, $context );
            }
            catch( Exception $e ){
                echo "Test execution failed while setup:" . $e . PHP_EOL;
                return TRUE;
            }

            try{
                $tested = $this->test( $action, $context );
                if ( $tested ){
                    $this->tests ++;
                }
            }
            catch( Exception $e ){
                echo "[Info]Caught exception:" . get_class($e) . PHP_EOL;
                if ( $this->expected_exception ){
                    if ( $this->expected_exception != get_class($e) ){
                        $expected = $this->expected_exception;
                        $actual = get_class($e);
                        $this->message2( get_class($e), "Expected", "Actual", $expected, $actual );
                    }
                }
                else{
                    echo "[Warning]Test execution failed while test:" . $e . PHP_EOL;
                }
            }

            try{
                $this->cleanUp( $action, $context );
            }
            catch( Exception $e ){
                echo "Test execution failed while clean up:" . $e . PHP_EOL;
                return TRUE;
            }

            echo "Action finished: [$action]" . PHP_EOL . PHP_EOL;
        }

        // 終了メッセージ
        if ( $this->tests > 0 ){
            echo "Tests complete! : [$section]" . PHP_EOL . PHP_EOL;
            echo "Tests: {$this->tests} Assertions: {$this->asserts} Failures: {$this->failures}" . PHP_EOL;
        }
        else{
            echo "No tests were processed." . PHP_EOL;
        }
        echo "===================================================" . PHP_EOL . PHP_EOL;

        return TRUE;
    }
}


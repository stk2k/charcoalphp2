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
    private $action;
    private $expected_exception;
    private $verbose;

    /**
     * Initialize instance
     *
     * @param Charcoal_Config $config   configuration data
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
     * set verbose flag
     *
     * @param boolean|Charcoal_Boolean $verbose
     */
    public function setVerbose( $verbose ){
        $this->verbose = ub($verbose);
    }

    /**
     * get verbose flag
     *
     * @return boolean
     */
    public function getVerbose(){
        return $this->verbose;
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
     * @param Exception $expected_exception
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

        echo "[ASSERT] $result" . eol();
        echo "  $value1_title: " . $value1 . eol();
        echo "  $value2_title: " . Charcoal_System::toString($value2,TRUE) . eol();
        echo "  $file($line)" . eol();

        $this->asserts ++;
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
     * assert if NULL
     *
     * @param mixed $actual
     */
    public function assertNull( $actual )
    {
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $this->verbose ){
            echo __METHOD__ . "($actual_s)" . eol();
        }
        $this->tests ++;
        if ( $actual !== NULL ){
            $this->messageExpectedActual( "Null", "=== NULL", $actual_s );
        }
    }

    /**
     * assert if NOT NULL
     *
     * @param mixed $actual
     */
    public function assertNotNull( $actual )
    {
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $this->verbose ){
            echo __METHOD__ . "($actual_s)" . eol();
        }
        $this->tests ++;
        if ( $actual === NULL ){
            $this->messageExpectedActual( "Not Null", "=== NULL", $actual_s );
        }
    }

    /**
     * assert if empty
     *
     * @param mixed $actual
     */
    public function assertEmpty( $actual )
    {
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $this->verbose ){
            echo __METHOD__ . "($actual_s)" . eol();
        }
        $this->tests ++;
        if ( !empty($actual) ){
            $this->messageExpectedActual( "Empty", "''", $actual_s );
        }
    }

    /**
     * assert if NOT empty
     *
     * @param mixed $actual
     */
    public function assertNotEmpty( $actual )
    {
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $this->verbose ){
            echo __METHOD__ . "($actual_s)" . eol();
        }
        $this->tests ++;
        if ( empty($actual) ){
            $this->messageExpectedActual( "Not Empty", "''", $actual_s );
        }
    }

    /**
     * assert if equal
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function assertEquals( $expected, $actual )
    {
        $expected_s = Charcoal_System::toString($expected,TRUE);
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $this->verbose ){
            echo __METHOD__ . "($expected_s, $actual_s)" . eol();
        }
        $this->tests ++;
        if ( $expected != $actual ){
            $this->messageExpectedActual( "Not Equal", "== $expected_s", $actual_s );
        }
    }

    /**
     * assert if NOT equal
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function assertNotEquals( $expected, $actual )
    {
        $expected_s = Charcoal_System::toString($expected,TRUE);
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $this->verbose ){
            echo __METHOD__ . "($expected_s, $actual_s)" . eol();
        }
        $this->tests ++;
        if ( $expected == $actual ){
            $expected = Charcoal_System::toString($expected,TRUE);
            $this->messageExpectedActual( "Equal", "!= $expected_s", $actual_s );
        }
    }

    /**
     * assert if same
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function assertSame( $expected, $actual )
    {
        $expected_s = Charcoal_System::toString($expected,TRUE);
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $this->verbose ){
            echo __METHOD__ . "($expected_s, $actual_s)" . eol();
        }
        $this->tests ++;
        if ( $expected !== $actual ){
            $expected = Charcoal_System::toString($expected,TRUE);
            $this->messageExpectedActual( "Not Same", "=== $expected_s", $actual_s );
        }
    }

    /**
     * assert if NOT same
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function assertNotSame( $expected, $actual )
    {
        $expected_s = Charcoal_System::toString($expected,TRUE);
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $this->verbose ){
            echo __METHOD__ . "($expected_s, $actual_s)" . eol();
        }
        $this->tests ++;
        if ( $expected === $actual ){
            $expected = Charcoal_System::toString($expected,TRUE);
            $this->messageExpectedActual( "Same", "!== $expected_s", $actual_s );
        }
    }

    /**
     * assert if FALSE
     *
     * @param mixed $actual
     */
    public function assertFalse( $actual )
    {
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $this->verbose ){
            echo __METHOD__ . "($actual_s)" . eol();
        }
        $this->tests ++;
        if ( $actual !== FALSE ){
            $this->messageExpectedActual( "Not FALSE", "=== FALSE", $actual_s );
        }
    }

    /**
     * assert if TRUE
     *
     * @param mixed $actual
     */
    public function assertTrue( $actual )
    {
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $this->verbose ){
            echo __METHOD__ . "($actual_s)" . eol();
        }
        $this->tests ++;
        if ( $actual !== TRUE ){
            $this->messageExpectedActual( "Not TRUE", "=== TRUE", $actual_s );
        }
    }

    /**
     * assert if TRUE
     *
     * @param mixed $needle
     * @param array $haystack
     */
    public function assertCotains( $needle, $haystack )
    {
        $needle_s = Charcoal_System::toString($needle,TRUE);
        $haystack_s = Charcoal_System::toString($haystack,TRUE);
        if ( $this->verbose ){
            echo __METHOD__ . "($needle_s, $haystack_s)" . eol();
        }
        $this->tests ++;
        if ( in_array($needle, $haystack) ){
            $this->messageNeedleHaystack( "Not Contains", $needle_s, $haystack_s );
        }
    }

    /**
     * assert if greater than
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function assertGreaterThan( $expected, $actual )
    {
        $expected_s = Charcoal_System::toString($expected,TRUE);
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $this->verbose ){
            echo __METHOD__ . "($expected_s, $actual_s)" . eol();
        }
        $this->tests ++;
        if ( $expected >= $actual ){
            $this->messageExpectedActual( "Less than or equal", ">= $expected_s", $actual_s );
        }
    }

    /**
     * assert if greater than or equal
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function assertGreaterThanOrEqual( $expected, $actual )
    {
        $expected_s = Charcoal_System::toString($expected,TRUE);
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $this->verbose ){
            echo __METHOD__ . "($expected_s, $actual_s)" . eol();
        }
        $this->tests ++;
        if ( $expected > $actual ){
            $this->messageExpectedActual( "Less than", "> $expected_s", $actual_s );
        }
    }

    /**
     * assert if less than
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function assertLessThan( $expected, $actual )
    {
        $expected_s = Charcoal_System::toString($expected,TRUE);
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $this->verbose ){
            echo __METHOD__ . "($expected_s, $actual_s)" . eol();
        }
        $this->tests ++;
        if ( $expected <= $actual ){
            $this->messageExpectedActual( "Greater than or equal", "<= $expected_s", $actual_s );
        }
    }

    /**
     * assert if less than or equal
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    public function assertLessThanOrEqual( $expected, $actual )
    {
        $expected_s = Charcoal_System::toString($expected,TRUE);
        $actual_s = Charcoal_System::toString($actual,TRUE);
        if ( $this->verbose ){
            echo __METHOD__ . "($expected_s, $actual_s)" . eol();
        }
        $this->tests ++;
        if ( $expected < $actual ){
            $this->messageExpectedActual( "Less than", "< $expected_s", $actual_s );
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
        /** @var Charcoal_TestEvent $event */
        $event   = $context->getEvent();

        $is_debug = $context->isDebug();

        // パラメータを取得
        $section       = $event->getSection();
        $target        = $event->getTarget();
        $actions       = $event->getActions();

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
        $errors = 0;

        echo eol();
        echo "==========================================" . eol();
        echo "CharcoalPHP Test Runner" . eol();
        echo "   Framework Version:" . Charcoal_Framework::getVersion() . eol();
        echo "==========================================" . eol();

        echo "Title:" . $section . eol();
        echo "TargetTask:" . $this->getObjectName() . eol();
        echo "Test started(total=$total_actions)." . eol();

        foreach( $actions as $action ){
            $action = trim( $action );
            if ( strlen($action) === 0 )    continue;

            $this->action = $action;

            if ( !$this->isValidAction( $action ) )    continue;

            echo "-------------------------------------" . eol();
            echo "$action" . eol();

            try{
                $this->setUp( $action, $context );
            }
            catch( Exception $e ){
                echo "Test execution failed while setup:" . $e . eol();
                return TRUE;
            }

            try{
                $this->test( $action, $context );
            }
            catch( Exception $e ){
                echo "[Info]Caught exception:" . get_class($e) . eol();
                if ( $this->expected_exception ){
                    if ( $this->expected_exception != get_class($e) ){
                        $expected = $this->expected_exception;
                        $actual = get_class($e);
                        $this->message2( get_class($e), "Expected", "Actual", $expected, $actual );
                    }
                }
                else{
                    echo "[Warning]Test execution failed while test:" . $e . eol();
                }
            }

            try{
                $this->cleanUp( $action, $context );
            }
            catch( Exception $e ){
                echo "Test execution failed while clean up:" . $e . eol();
                return TRUE;
            }

        }

        // 終了メッセージ
        echo "-------------------------------------" . eol();
        if ( $this->tests > 0 ){
            echo "Tests complete!" . eol();
            echo "Tests: {$this->tests} Assertions: {$this->asserts} Errors: $errors" . eol();
        }
        else{
            echo "No tests were processed." . eol();
        }

        return TRUE;
    }
}


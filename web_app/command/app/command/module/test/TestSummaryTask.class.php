<?php
/**
* Command Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class TestSummaryTask extends Charcoal_Task
{
    private $tests;
    private $assertions;
    private $failures;

    private $section_map;

    /**
     * Initialize instance
     *
     * @param array $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );

        $this->tests = 0;
        $this->assertions = 0;
        $this->failures = 0;

        $this->section_map = array();
    }

    /**
     * process event
     *
     * @param Charcoal_IEventContext $context   event context
     *
     * @return Charcoal_Boolean|bool
     */
    public function processEvent( $context )
    {
        $event = $context->getEvent();

        if ( $event instanceof Charcoal_TestResultEvent ){
            /** @var Charcoal_TestResultEvent $event */
            $section = $event->getSection();
            $action = $event->getAction();
            $result = $event->getSuccess();

            $this->assertions ++;
            if ( !$result ){
                $this->failures ++;
            }

            if ( !isset($this->section_map[$section]) ){
                $this->section_map[$section] = array(array(), 0, 0);
            }

            list( $tests, $assertions, $failures ) = $this->section_map[$section];

            $assertions ++;
            if ( !$result ){
                $failures ++;
            }
            if ( !in_array($action,$tests) ){
                $tests[] = $action;
                $this->tests ++;
            }

            $this->section_map[$section] = array( $tests, $assertions, $failures );
        }
        else if ( $event instanceof Charcoal_TestSummaryEvent ){

            if ( !$context->getEventQueue()->isEmpty() ){
                // 他にイベントが残っている場合は処理しない
                return FALSE;
            }

            echo PHP_EOL;
            echo "==========================================" . PHP_EOL;
            echo "Test Result Summary" . PHP_EOL;
            echo " --------------------------------------- " . PHP_EOL;

            echo " Tests/Assertions/Failures by section:" . PHP_EOL . PHP_EOL;
            foreach( $this->section_map as $section => $map ){
                list( $tests, $assertions, $failures ) = $map;

                echo "   [$section] " . count($tests) . " / $assertions / $failures" . PHP_EOL;
            }

            echo " --------------------------------------- " . PHP_EOL;
            echo " Total:" . PHP_EOL . PHP_EOL;
            echo "  Tests: " . $this->tests . PHP_EOL;
            echo "  Assertions: " . $this->assertions . PHP_EOL;
            echo "  Failures: " . $this->failures . PHP_EOL;
            echo "==========================================" . PHP_EOL;

        }

        return TRUE;
    }
}

return __FILE__;
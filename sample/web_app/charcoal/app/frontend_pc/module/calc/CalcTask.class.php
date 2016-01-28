<?php
/**
* Hello Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/
class DivisionByZeroException extends Exception
{
    public function __construct()
    {
        parent::__construct( 'divided by zero' );
    }
}

class CalcTask extends Charcoal_Task
{
    /**
     * process event
     *
     * @param Charcoal_IEventContext $context   event context
     *
     * @return boolean|Charcoal_Boolean
     *
     * @throws DivisionByZeroException
     */
    public function processEvent( $context )
    {
        $request   = $context->getRequest();

        // Get parameter from request
        $a = $request->getInteger( 'a', 0 );
        $b = $request->getInteger( 'b', 0 );
        $op = $request->getString( 'op', '+' );

        $a = ui($a);
        $b = ui($b);
        $op = us($op);

        $result = NULL;
        switch( $op ){
        case 'add':
            $result = $a + $b;
            break;
        case 'sub':
            $result = $a - $b;
            break;
        case 'mul':
            $result = $a * $b;
            break;
        case 'div':
            if ( $b == 0 ){
                throw new DivisionByZeroException();
            }
            $result = $a / $b;
            break;
        }

        // show message
        if ( $result ){
            echo "result:" . $result . eol();
        }
        else{
            echo "<pre>USAGE:" . PHP_EOL;

            echo "http://" . $_SERVER['SERVER_NAME'] . "/calc/value1/value2/[add/sub/mul/div]" . PHP_EOL;
            echo "value1, value2: number" . eol();
            echo "add: shows result of 'value1 + value2'" . PHP_EOL;
            echo "sub: shows result of 'value1 - value2'" . PHP_EOL;
            echo "mul: shows result of 'value1 * value2'" . PHP_EOL;
            echo "div: shows result of 'value1 / value2'" . PHP_EOL;

            echo "</pre>" . eol();
        }

        // return TRUE if processing the procedure success.
        return TRUE;
    }

    /**
     * execute exception handlers
     *
     * @param Exception $e     exception to handle
     * @param Charcoal_IEventContext $context   event context
     *
     * @return boolean        TRUE means the exception is handled, otherwise FALSE
     *
     * @throws Charcoal_HttpStatusException
     */
    public function handleException( $e, $context )
    {
        echo "Exception:" . $e->getMessage() . "<br>";
        throw new Charcoal_HttpStatusException(500);
    }

}

return __FILE__;
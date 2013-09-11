<?php
/**
* Framework Test Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/
class FrameworkTestTask extends Charcoal_TestTask
{
	/**
	 * check if action will be processed
	 */
	public function isValidAction( Charcoal_String $action )
	{
		switch( $action ){
		case "push_procedure":
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * setup test
	 */
	public function setUp( Charcoal_String $action )
	{
	}

	/**
	 * clean up test
	 */
	public function cleanUp( Charcoal_String $action )
	{
	}

	/**
	 * execute tests
	 */
	public function test( Charcoal_String $action, Charcoal_IEventContext $context )
	{
		$action = us($action);

		switch( $action ){
		case "push_procedure":

			$proc = new Charcoal_HttpProcedure();
			$proc_id = $proc->hash();

			Charcoal_Framework::pushProcedure( $proc );

			$stack = Charcoal_Framework::getProcedureStack();
			$this->assertEquals( 1, $stack->count() );

			$top_proc = $stack->pop();
			$this->assertEquals( 0, $stack->count() );

			$top_proc_id = $top_proc->hash();

			echo "proc_id:$proc_id" . eol();
			echo "top_proc_id:$top_proc_id" . eol();

			$this->assertEquals( $proc_id, $top_proc_id );
			
			return TRUE;

		}

		return FALSE;
	}

}

return __FILE__;
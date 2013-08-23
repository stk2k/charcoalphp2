<?php
/**
* System Test Task
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/
class GrandParentClass
{
	public    $grandparent_public_var = 1;
	protected $grandparent_protected_var = 2;
	private   $grandparent_private_var = 3;

	public    $public_var = 1;
	protected $protected_var = 2;
	private   $private_var = 3;

	public function getObjectVars()
	{
		return get_object_vars($this);
	}
}
class ParentClass extends GrandParentClass
{
	public function getObjectVars()
	{
		return array_merge(parent::getObjectVars(), get_object_vars($this) );
	}
}
class ChildClass extends ParentClass
{
	public    $child_public_var = 4;
	protected $child_protected_var = 5;
	private   $child_private_var = 6;

	public    $public_var = 4;
	protected $protected_var = 5;
	private   $private_var = 6;

	public function getObjectVars()
	{
		return array_merge(parent::getObjectVars(), get_object_vars($this) );
	}
}
class GrandChildClass extends ChildClass
{
	public    $grandchild_public_var = 7;
	protected $grandchild_protected_var = 8;
	private   $grandchild_private_var = 9;

	public    $public_var = 7;
	protected $protected_var = 8;
	private   $private_var = 9;

	public function getObjectVars()
	{
		return array_merge(parent::getObjectVars(), get_object_vars($this) );
	}
}

class SystemTestTask extends Charcoal_TestTask
{
	/**
	 * check if action will be processed
	 */
	public function isValidAction( Charcoal_String $action )
	{
		switch( $action ){
		case "get_object_vars":
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
		case "get_object_vars":
			$grandchild = new GrandChildClass();

			$grandchild_vars = Charcoal_System::getObjectVars( $grandchild );

//			foreach( $grandchild_vars as $k => $v ){
//				echo "grandchild vars: [$k]=$v" . eol();
//			}

			$grandchild_vars_actual = $grandchild->getObjectVars();

//			foreach( $grandchild_vars_actual as $k => $v ){
//				echo "grandchild vars_actual: [$k]=$v" . eol();
//			}

			$this->assertEquals( 1, $grandchild_vars_actual['grandparent_public_var'] );
			$this->assertEquals( 2, $grandchild_vars_actual['grandparent_protected_var'] );
			$this->assertEquals( 3, $grandchild_vars_actual['grandparent_private_var'] );
			$this->assertEquals( 4, $grandchild_vars_actual['child_public_var'] );
			$this->assertEquals( 5, $grandchild_vars_actual['child_protected_var'] );
			$this->assertEquals( 6, $grandchild_vars_actual['child_private_var'] );
			$this->assertEquals( 7, $grandchild_vars_actual['grandchild_public_var'] );
			$this->assertEquals( 8, $grandchild_vars_actual['grandchild_protected_var'] );
			$this->assertEquals( 9, $grandchild_vars_actual['grandchild_private_var'] );
			$this->assertEquals( 7, $grandchild_vars_actual['public_var'] );
			$this->assertEquals( 8, $grandchild_vars_actual['protected_var'] );
			$this->assertEquals( 9, $grandchild_vars_actual['private_var'] );

			$child = new ChildClass();

			$child_vars = Charcoal_System::getObjectVars( $child );

//			foreach( $child_vars as $k => $v ){
//				echo "child vars: [$k]=$v" . eol();
//			}

			$child_vars_actual = $child->getObjectVars();

//			foreach( $child_vars_actual as $k => $v ){
//				echo "child vars_actual: [$k]=$v" . eol();
//			}

			$this->assertEquals( 1, $child_vars_actual['grandparent_public_var'] );
			$this->assertEquals( 2, $child_vars_actual['grandparent_protected_var'] );
			$this->assertEquals( 3, $child_vars_actual['grandparent_private_var'] );
			$this->assertEquals( 4, $child_vars_actual['child_public_var'] );
			$this->assertEquals( 5, $child_vars_actual['child_protected_var'] );
			$this->assertEquals( 6, $child_vars_actual['child_private_var'] );
			$this->assertEquals( 4, $child_vars_actual['public_var'] );
			$this->assertEquals( 5, $child_vars_actual['protected_var'] );
			$this->assertEquals( 6, $child_vars_actual['private_var'] );

			$parent = new ParentClass();

			$parent_vars = Charcoal_System::getObjectVars( $parent );

//			foreach( $parent_vars as $k => $v ){
//				echo "parent vars: [$k]=$v" . eol();
//			}

			$parent_vars_actual = $parent->getObjectVars();

//			foreach( $parent_vars_actual as $k => $v ){
//				echo "parent vars_actual: [$k]=$v" . eol();
//			}

			$this->assertEquals( 1, $parent_vars_actual['grandparent_public_var'] );
			$this->assertEquals( 2, $parent_vars_actual['grandparent_protected_var'] );
			$this->assertEquals( 3, $parent_vars_actual['grandparent_private_var'] );
			$this->assertEquals( 1, $parent_vars_actual['public_var'] );
			$this->assertEquals( 2, $parent_vars_actual['protected_var'] );
			$this->assertEquals( 3, $parent_vars_actual['private_var'] );

			return TRUE;
		}

		return FALSE;
	}

}

return __FILE__;
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
	public function isValidAction( $action )
	{
		switch( $action ){
		case "get_object_var":
		case "get_object_vars":
		case "snake_case":
		case "pascal_case":
		case "hash_collision":
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * setup test
	 */
	public function setUp( $action, $context )
	{
	}

	/**
	 * clean up test
	 */
	public function cleanUp( $action, $context )
	{
	}

	/**
	 * execute tests
	 */
	public function test( $action, $context )
	{
		$action = us($action);

		switch( $action ){
		case "get_object_var":
			$child = new ChildClass();

			$actual = Charcoal_System::getObjectVar( $child, 'child_public_var' );
			$this->assertEquals( 4, $actual );
			
			$actual = Charcoal_System::getObjectVar( $child, 'child_protected_var' );
			$this->assertEquals( 5, $actual );
			
			$actual = Charcoal_System::getObjectVar( $child, 'child_private_var' );
			$this->assertEquals( 6, $actual );
			
			$actual = Charcoal_System::getObjectVar( $child, 'public_var' );
			$this->assertEquals( 4, $actual );
			
			$actual = Charcoal_System::getObjectVar( $child, 'protected_var' );
			$this->assertEquals( 5, $actual );
			
			$actual = Charcoal_System::getObjectVar( $child, 'private_var' );
			$this->assertEquals( 6, $actual );
			
			return TRUE;

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

		case "snake_case":

			$data = array(

					'CharcoalPhp' => 'charcoal_php',
					'CharcoalPHP' => 'charcoal_php',
					'charcoalPhp' => 'charcoal_php',
					'charcoalPHP' => 'charcoal_php',
					'charcoalphp' => 'charcoalphp',
					'Charcoalphp' => 'charcoalphp',
					'charcoalpHp' => 'charcoalp_hp',
					'charcoalphP' => 'charcoalph_p',
					'charCoalphp' => 'char_coalphp',
					'charcoal_php' => 'charcoal_php',
					'charcoal_PHP' => 'charcoal_php',
					'Charcoal_PHP' => 'charcoal_php',
					'Charcoal_Php' => 'charcoal_php',
					'Charcoal_pHp' => 'charcoal_p_hp',
					'Charcoal_phP' => 'charcoal_ph_p',
					'CharCoal_php' => 'char_coal_php',
					'charcoal_php_5.3' => 'charcoal_php_5.3',
					'charcoal_php_ver5.3' => 'charcoal_php_ver5.3',
					'charcoal_php_Ver5.3' => 'charcoal_php_ver5.3',
					'charcoal_php_vEr5.3' => 'charcoal_php_v_er5.3',
					'charcoal_php_5.3-dev' => 'charcoal_php_5.3-dev',
					'charcoal_php_5.3-Dev' => 'charcoal_php_5.3-dev',
					'charcoal_php_5.3-dEv' => 'charcoal_php_5.3-d_ev',
					'charcoal_php0a2c' => 'charcoal_php0a2c',
				);

			foreach( $data as $key => $expected ){
				$actual = Charcoal_System::snakeCase( $key );
//				echo "[original]$key [expected]$expected [actual]$actual" . eol();
				$this->assertEquals( $expected, $actual );
			}

			return TRUE;

		case "pascal_case":

			$data = array(

					'CharcoalPhp' => 'Charcoalphp',
					'CharcoalPHP' => 'Charcoalphp',
					'charcoalPhp' => 'Charcoalphp',
					'charcoalPHP' => 'Charcoalphp',
					'charcoalphp' => 'Charcoalphp',
					'Charcoalphp' => 'Charcoalphp',
					'charcoalpHp' => 'Charcoalphp',
					'charcoalphP' => 'Charcoalphp',
					'charCoalphp' => 'Charcoalphp',
					'charcoal_php' => 'CharcoalPhp',
					'charcoal_PHP' => 'CharcoalPhp',
					'Charcoal_PHP' => 'CharcoalPhp',
					'Charcoal_Php' => 'CharcoalPhp',
					'Charcoal_pHp' => 'CharcoalPhp',
					'Charcoal_phP' => 'CharcoalPhp',
					'CharCoal_php' => 'CharcoalPhp',
					'charcoal_php_5.3' => 'CharcoalPhp5.3',
					'charcoal_php_ver5.3' => 'CharcoalPhpVer5.3',
					'charcoal_php_Ver5.3' => 'CharcoalPhpVer5.3',
					'charcoal_php_vEr5.3' => 'CharcoalPhpVer5.3',
					'charcoal_php_5.3-dev' => 'CharcoalPhp5.3-dev',
					'charcoal_php_5.3-Dev' => 'CharcoalPhp5.3-dev',
					'charcoal_php_5.3-dEv' => 'CharcoalPhp5.3-dev',
					'charcoal_php0a2c' => 'CharcoalPhp0a2c',
				);

			foreach( $data as $key => $expected ){
				$actual = Charcoal_System::pascalCase( $key );
//				echo "[original]$key [expected]$expected [actual]$actual" . eol();
				$this->assertEquals( $expected, $actual );
			}

			return TRUE;

		case "hash_collision":
			{
				$hashes = array();
				$max = 1000000;
				for($i=0; $i<$max; $i++){
					$hash = Charcoal_System::hash();
					if ( !isset($hashes[$hash]) ){
						$hashes[$hash]  = 0;
					}
					$hashes[$hash] ++;
					$p = ((float)$i) / $max * 100;
					echo sprintf("[%0.2f %%]\r",$p);
				}
				$collisions = array_filter( $hashes, function($item){
					return $item >= 2;
				});
				ad($collisions);
			}

			return TRUE;

		}

		return FALSE;
	}

}

return __FILE__;
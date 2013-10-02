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
class MyDTO extends Charcoal_DTO
{
	public $foo;
	public $bar;
}

class DtoTestTask extends Charcoal_TestTask
{
	/**
	 * check if action will be processed
	 */
	public function isValidAction( $action )
	{
		switch( $action ){
		case "dto_array_access":
		case "dto_keys":
		case "dto_offset_get":
		case "dto_magic_get":
		case "dto_offset_set":
		case "dto_magic_set":
		case "dto_offset_exists":
		case "dto_offset_unset":
		case "dto_set_array":
		case "dto_set_hashmap":
		case "dto_merge_array":
		case "dto_merge_hashmap":
		case "dto_to_array":
		case "dto_foreach":
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
		case "dto_array_access":
			$dto = new MyDTO();

			$dto["foo"] = 1;
			$dto["bar"] = "apple";
			$dto["baz"] = NULL;
			$dto["qux"] = 0.5;

			//echo print_r($dto,true);

			// •]‰¿
			$this->assertEquals( 1, $dto["foo"] );
			$this->assertEquals( "apple", $dto["bar"] );
			$this->assertEquals( NULL, $dto["baz"] );
			$this->assertEquals( 0.5, $dto["qux"] );

			return TRUE;

		case "dto_keys":
			$dto = new MyDTO();

			$dto["foo"] = 1;
			$dto["bar"] = "apple";
			$dto["baz"] = NULL;
			$dto["qux"] = 0.5;

			$keys = $dto->getKeys();

			//echo "keys:" . print_r($keys,true) . eol();

			$this->assertEquals( "foo", $keys[0] );
			$this->assertEquals( "bar", $keys[1] );
			$this->assertEquals( "baz", $keys[2] );
			$this->assertEquals( "qux", $keys[3] );

			return TRUE;

		case "dto_offset_get":
			$dto = new MyDTO();

			$dto["foo"] = 1;
			$dto["bar"] = "apple";
			$dto["baz"] = NULL;
			$dto["qux"] = 0.5;

			//echo "dto[foo]:" . $dto["foo"] . eol();
			//echo "dto[bar]:" . $dto["bar"] . eol();
			//echo "dto[baz]:" . $dto["baz"] . eol();
			//echo "dto[qux]:" . $dto["qux"] . eol();

			$this->assertEquals( 1, $dto["foo"] );
			$this->assertEquals( "apple", $dto["bar"] );
			$this->assertEquals( NULL, $dto["baz"] );
			$this->assertEquals( 0.5, $dto["qux"] );

			//echo "dto->foo:" . $dto->foo . eol();
			//echo "dto->bar:" . $dto->bar . eol();
			//echo "dto->baz:" . $dto->baz . eol();
			//echo "dto->qux:" . $dto->qux . eol();

			$this->assertEquals( 1, $dto->foo );
			$this->assertEquals( "apple", $dto->bar );
			$this->assertEquals( NULL, $dto->baz );
			$this->assertEquals( 0.5, $dto->qux );

			return TRUE;

		case "dto_magic_get":
			$dto = new MyDTO();

			$this->assertEquals( NULL, $dto->foo );

			$dto["foo"] = 1;

			$this->assertEquals( 1, $dto->foo );

			unset($dto["foo"]);

			$this->assertEquals( NULL, $dto->foo );

			return TRUE;

		case "dto_offset_set":
			$dto = new MyDTO();

			$dto->foo = 1;
			$dto->bar = "apple";
			$dto->baz = NULL;
			$dto->qux = 0.5;

			//echo "dto[foo]:" . $dto["foo"] . eol();
			//echo "dto[bar]:" . $dto["bar"] . eol();
			//echo "dto[baz]:" . $dto["baz"] . eol();
			//echo "dto[qux]:" . $dto["qux"] . eol();

			$this->assertEquals( 1, $dto["foo"] );
			$this->assertEquals( "apple", $dto["bar"] );
			$this->assertEquals( NULL, $dto["baz"] );
			$this->assertEquals( 0.5, $dto["qux"] );

			//echo "dto->foo:" . $dto->foo . eol();
			//echo "dto->bar:" . $dto->bar . eol();
			//echo "dto->baz:" . $dto->baz . eol();
			//echo "dto->qux:" . $dto->qux . eol();

			$this->assertEquals( 1, $dto->foo );
			$this->assertEquals( "apple", $dto->bar );
			$this->assertEquals( NULL, $dto->baz );
			$this->assertEquals( 0.5, $dto->qux );

			return TRUE;


		case "dto_magic_set":
			$dto = new MyDTO();

			$this->assertEquals( NULL, $dto->foo );

			$dto->foo = 1;

			$this->assertEquals( 1, $dto->foo );

			unset($dto["foo"]);
			//unset($dto->foo);		// NG!!

			$this->assertEquals( NULL, $dto->foo );

			return TRUE;

		case "dto_offset_exists":

			$dto = new Charcoal_DTO();

			$this->assertEquals( FALSE, isset($dto["foo"]) );

			$dto->foo = 1;

			$this->assertEquals( TRUE, isset($dto["foo"]) );

			unset($dto["foo"]);
			//unset($dto->foo);		// NG!!

			$this->assertEquals( FALSE, isset($dto["foo"]) );

			$dto2 = new MyDTO();

			$this->assertEquals( TRUE, isset($dto2["foo"]) );

			$dto2->foo = 1;

			$this->assertEquals( TRUE, isset($dto2["foo"]) );

			unset($dto2["foo"]);
			//unset($dto->foo);		// NG!!

			$this->assertEquals( TRUE, isset($dto2["foo"]) );

			return TRUE;

		case "dto_offset_unset":
			$dto = new MyDTO();

			$this->assertEquals( NULL, $dto->foo );
			$this->assertEquals( NULL, $dto["foo"] );

			$dto->foo = 1;

			$this->assertEquals( 1, $dto->foo );
			$this->assertEquals( 1, $dto["foo"] );

			unset($dto["foo"]);
			//unset($dto->foo);		// NG!!

			$this->assertEquals( NULL, $dto->foo );
			$this->assertEquals( NULL, $dto["foo"] );

			return TRUE;

		case "dto_set_array":
			$dto = new MyDTO();

			$data = array(
					"foo" => 1, 
					"bar" => "apple", 
					"baz" => NULL, 
					"qux" => 0.5,
				);

			$dto->setArray( $data );

//			echo "dto:" . print_r($dto,true) . eol();

//			echo "dto[foo]:" . $dto["foo"] . eol();
//			echo "dto[bar]:" . $dto["bar"] . eol();
//			echo "dto[baz]:" . $dto["baz"] . eol();
//			echo "dto[qux]:" . $dto["qux"] . eol();

			$this->assertEquals( 1, $dto["foo"] );
			$this->assertEquals( "apple", $dto["bar"] );
			$this->assertEquals( NULL, $dto["baz"] );
			$this->assertEquals( 0.5, $dto["qux"] );

			return TRUE;

		case "dto_set_hashmap":
			$dto = new MyDTO();

			$data = array(
					"foo" => 1, 
					"bar" => "apple", 
					"baz" => NULL, 
					"qux" => 0.5,
				);

			$dto->setHashMap( $data );

//			echo "dto[foo]:" . $dto["foo"] . eol();
//			echo "dto[bar]:" . $dto["bar"] . eol();
//			echo "dto[baz]:" . $dto["baz"] . eol();
//			echo "dto[qux]:" . $dto["qux"] . eol();

			$this->assertEquals( 1, $dto["foo"] );
			$this->assertEquals( "apple", $dto["bar"] );
			$this->assertEquals( NULL, $dto["baz"] );
			$this->assertEquals( 0.5, $dto["qux"] );

			return TRUE;

		case "dto_merge_array":
			$dto = new MyDTO();

			$dto->foo = 1;
			$dto->bar = "apple";
			$dto->baz = NULL;
			$dto->qux = 0.5;

			$data = array(
					"foo" => 2, 
					"bar" => "banana", 
				);

			$dto->mergeArray( array("foo" => 2, "bar" => "banana") );
			$dto->mergeArray( array("baz" => -1, "qux" => 1.2), FALSE );

//			echo "dto[foo]:" . $dto["foo"] . eol();
//			echo "dto[bar]:" . $dto["bar"] . eol();
//			echo "dto[baz]:" . $dto["baz"] . eol();
//			echo "dto[qux]:" . $dto["qux"] . eol();

			$this->assertEquals( 2, $dto["foo"] );
			$this->assertEquals( "banana", $dto["bar"] );
			$this->assertEquals( NULL, $dto["baz"] );
			$this->assertEquals( 0.5, $dto["qux"] );

			$dto->mergeArray( array("foo" => 3, "bar" => "peach"), TRUE );

//			echo "dto[foo]:" . $dto["foo"] . eol();
//			echo "dto[bar]:" . $dto["bar"] . eol();
//			echo "dto[baz]:" . $dto["baz"] . eol();
//			echo "dto[qux]:" . $dto["qux"] . eol();

			$this->assertEquals( 3, $dto["foo"] );
			$this->assertEquals( "peach", $dto["bar"] );
			$this->assertEquals( NULL, $dto["baz"] );
			$this->assertEquals( 0.5, $dto["qux"] );

			return TRUE;

		case "dto_merge_hashmap":

			$dto = new MyDTO();

			$dto->foo = 1;
			$dto->bar = "apple";
			$dto->baz = NULL;
			$dto->qux = 0.5;

			$data = array(
					"foo" => 2, 
					"bar" => "banana", 
				);

			$dto->mergeHashMap( array("foo" => 2, "bar" => "banana") );
			$dto->mergeHashMap( array("baz" => -1, "qux" => 1.2), FALSE );

//			echo "dto[foo]:" . $dto["foo"] . eol();
//			echo "dto[bar]:" . $dto["bar"] . eol();
//			echo "dto[baz]:" . $dto["baz"] . eol();
//			echo "dto[qux]:" . $dto["qux"] . eol();

			$this->assertEquals( 2, $dto["foo"] );
			$this->assertEquals( "banana", $dto["bar"] );
			$this->assertEquals( NULL, $dto["baz"] );
			$this->assertEquals( 0.5, $dto["qux"] );

			$dto->mergeHashMap( array("foo" => 3, "bar" => "peach"), TRUE );

//			echo "dto[foo]:" . $dto["foo"] . eol();
//			echo "dto[bar]:" . $dto["bar"] . eol();
//			echo "dto[baz]:" . $dto["baz"] . eol();
//			echo "dto[qux]:" . $dto["qux"] . eol();

			$this->assertEquals( 3, $dto["foo"] );
			$this->assertEquals( "peach", $dto["bar"] );
			$this->assertEquals( NULL, $dto["baz"] );
			$this->assertEquals( 0.5, $dto["qux"] );

			return TRUE;

		case "dto_to_array":

			$dto = new MyDTO();

			$dto->foo = 1;
			$dto->bar = "apple";
			$dto->baz = NULL;
			$dto->qux = 0.5;

			$dto_array = $dto->toArray();

			$this->assertEquals( "array", gettype($dto_array) );
			$this->assertEquals( 1, $dto_array["foo"] );
			$this->assertEquals( "apple", $dto_array["bar"] );
			$this->assertEquals( NULL, $dto_array["baz"] );
			$this->assertEquals( 0.5, $dto_array["qux"] );

			return TRUE;

		case "dto_foreach":

			$dto = new MyDTO();

			$dto->foo = 1;
			$dto->bar = "apple";
			$dto->baz = NULL;
			$dto->qux = 0.5;

			$expected = array(
					"foo" => 1, 
					"bar" => "apple", 
					"baz" => NULL, 
					"qux" => 0.5,
				);

			foreach( $dto as $key => $value ){
//				echo "expected=[" . $expected[$key] . "] actual=[$value]" . eol();
				$this->assertEquals( $expected[$key], $value );
			}

			return TRUE;
		}

		return FALSE;
	}

}

return __FILE__;
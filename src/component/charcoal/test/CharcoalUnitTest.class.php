<?php
/**
* CharcoalUnitTestコンポーネント
*
* PHP version 5
*
* @package    component.charcoal.test
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
require_once "PHPUnit.php";


class Charcoal_CharcoalUnitTest extends Charcoal_CharcoalObject implements Charcoal_IComponent
{
	private $_test_class_dir;

	/*
	 * コンストラクタ
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_test_class_dir = NULL;
	}

	/**
	 * Configure component
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		$this->_test_class_dir      = $config->getString( s('test_class_dir'), s('test_classes') );
	}

	/*
	 * クラスをテストする
	 * 
	 * @params target_class テスト対象クラス名
	 * @params options 
	 * 				test_class=テストクラス名<br>
	 * 				test_class_file=テストクラスファイル名
	 * 				target_class_file=テスト対象クラスファイル名
	 */
	public function testClass( ProcedurePath $proc_path, Charcoal_String $target_class, Charcoal_HashMap $options = NULL )
	{
		$test_class        = isset($options['test_class']) ? $options['test_class'] : NULL;
		$test_class_file   = isset($options['test_class_file']) ? $options['test_class_file'] : NULL;

		// テストクラス名
		if ( $test_class === NULL ){
			$test_class = $target_class . 'Test';
		}

		// テストクラスファイル名
		if ( $test_class_file === NULL ){
			$test_class_file = $test_class . CHARCOAL_CLASS_FILE_SUFFIX;
		}

		// テストクラスファイル読み込み
		$this->_readTestClassFile( $proc_path, s($test_class_file) );

		// テスト実行
		$suite = new PHPUnit_TestSuite( $test_class );
		$result = PHPUnit::run($suite);

		return $result->toHTML();
	}

	/*
	 * テストクラスファイルを読み込む
	 * 
	 */
	public function _readTestClassFile( ProcedurePath $proc_path, Charcoal_String $test_class_file )
	{
		// CHARCOAL/tests以下
		$path = ResourceLocator::getFrameworkPath( s('tests'), s($test_class_file) );
		$file = new Charcoal_File( s($path) );
		if ( $file->exists() ){
			require_once( $path );
			return;
		}

		// WEB_APP/tests以下
		$path = ResourceLocator::getApplicationPath( s('tests'), s($test_class_file) );
		$file = new Charcoal_File( s($path) );
		if ( $file->exists() ){
			require_once( $path );
			return;
		}
//print "path: $path<br>";

		_throw( new Charcoal_FileNotFoundException( $test_class_file ) );
	}
}





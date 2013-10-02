<?php
/**
* アップロードされたファイル情報を保持するクラス
*
* PHP version 5
*
* @package    class.util
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_UploadedFile extends Charcoal_File
{
	private $name;
	private $type;
	private $size;
	private $tmpname;
	private $error;

	/*
	 *  コンストラクタ
	 *
	 *  @param String userfile $_FILES変数のキー
	 */
	public function __construct( Charcoal_String $userfile )
	{
		$file = $_FILES[ us($userfile) ];
log_debug( "debug", "debug", "_FILES:" . print_r($_FILES,true) );
log_debug( "debug", "debug", "userfile:" . print_r($userfile,true) );
log_debug( "debug", "debug", "file:" . print_r($file,true) );


		$this->name     = $file['name'];
		$this->type     = $file['type'];
		$this->size     = $file['size'];
		$this->tmpname = $file['tmpname'];
		$this->error    = $file['error'];

		parent::__construct( s($file['tmpname']) );
	}

	/*
	 * クライアントマシンの元のファイル名
	 *
	 */
	public function getName()
	{
		return $this->name;
	}

	/*
	 *  ファイルの MIME 型
	 *
	 */
	public function getType()
	{
		return $this->type;
	}

	/*
	 *  ファイルのバイト単位のサイズ
	 *
	 */
	public function getSize()
	{
		return $this->size;
	}

	/*
	 *  テンポラリファイルの名前を取得
	 *
	 */
	public function getTmpName()
	{
		return $this->tmpname;
	}

	/*
	 *  テンポラリファイルを取得
	 *
	 */
	public function getTmpFile()
	{
		return new Charcoal_File( s($this->tmpname) );
	}

	/*
	 *  テンポラリファイルのコンテンツを取得
	 *
	 */
	public function getTmpFileContents()
	{
		if ( !file_exists($this->tmpname) || !is_readable($this->tmpname) ){
			return NULL;
		}
		return file_get_contents( $this->tmpname );
	}

	/*
	 *  エラーコード
	 *
	 */
	public function getError()
	{
		return $this->error;
	}

	/*
	 *  Fileオブジェクトに変換
	 *
	 */
	public function toFile()
	{
		return new Charcoal_File( s($this->tmpname) );
	}

	/*
	 *  オリジナルファイル名の拡張子を取得
	 *
	 */
	public function getExtension()
	{
		$pos = strrpos( $this->name, '.' );
		if ( is_int($pos) ){
			return substr( $this->name, $pos );
		}
		return '';
	}

	/*
	 *  エラーチェック
	 *
	 */
	public function checkErrors()
	{
		switch( $this->error ){
		case UPLOAD_ERR_OK:
			log_info( "system,debug", "UPLOAD_ERR_OK" );
			break;
		case UPLOAD_ERR_INIsize:
			log_info( "system,debug", "UPLOAD_ERR_INIsize" );
			_throw( new Charcoal_FileUploadIniSizeException($this) );
			break;
		case UPLOAD_ERR_FORMsize:
			log_info( "system,debug", "UPLOAD_ERR_FORMsize" );
			_throw( new Charcoal_FileUploadFormSizeException($this) );
			break;
		case UPLOAD_ERR_PARTIAL:
			log_info( "system,debug", "UPLOAD_ERR_PARTIAL" );
			_throw( new Charcoal_FileUploadPartialException($this) );
			break;
		case UPLOAD_ERR_NO_FILE:
			log_info( "system,debug", "UPLOAD_ERR_NO_FILE" );
			_throw( new Charcoal_FileUploadNoFileException($this) );
			break;
		case UPLOAD_ERR_NO_TMP_DIR:
			log_info( "system,debug", "UPLOAD_ERR_NO_TMP_DIR" );
			_throw( new Charcoal_FileUploadNoTmpDirException($this) );
			break;
		case UPLOAD_ERR_CANT_WRITE:
			log_info( "system,debug", "UPLOAD_ERR_CANT_WRITE" );
			_throw( new Charcoal_FileUploadCantWriteException($this) );
			break;
		case UPLOAD_ERR_EXTENSION:
			log_info( "system,debug", "UPLOAD_ERR_EXTENSION" );
			_throw( new Charcoal_FileUploadExtensionException($this) );
			break;
		default:
			log_warning( "system,debug", "unexpected upload error:" . $this->error );
			break;
		}
	}

}


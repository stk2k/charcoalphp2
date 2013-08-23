<?php
/**
* アップロードされたファイル情報を保持するクラス
*
* PHP version 5
*
* @package    extended
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_UploadedFile extends Charcoal_File
{
	var $_name;
	var $_type;
	var $_size;
	var $_tmp_name;
	var $_error;

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


		$this->_name     = $file['name'];
		$this->_type     = $file['type'];
		$this->_size     = $file['size'];
		$this->_tmp_name = $file['tmp_name'];
		$this->_error    = $file['error'];

		parent::__construct( s($file['tmp_name']) );
	}

	/*
	 * クライアントマシンの元のファイル名
	 *
	 */
	public function getName()
	{
		return $this->_name;
	}

	/*
	 *  ファイルの MIME 型
	 *
	 */
	public function getType()
	{
		return $this->_type;
	}

	/*
	 *  ファイルのバイト単位のサイズ
	 *
	 */
	public function getSize()
	{
		return $this->_size;
	}

	/*
	 *  テンポラリファイルの名前を取得
	 *
	 */
	public function getTmpName()
	{
		return $this->_tmp_name;
	}

	/*
	 *  テンポラリファイルを取得
	 *
	 */
	public function getTmpFile()
	{
		return new Charcoal_File( s($this->_tmp_name) );
	}

	/*
	 *  テンポラリファイルのコンテンツを取得
	 *
	 */
	public function getTmpFileContents()
	{
		if ( !file_exists($this->_tmp_name) || !is_readable($this->_tmp_name) ){
			return NULL;
		}
		return file_get_contents( $this->_tmp_name );
	}

	/*
	 *  エラーコード
	 *
	 */
	public function getError()
	{
		return $this->_error;
	}

	/*
	 *  Fileオブジェクトに変換
	 *
	 */
	public function toFile()
	{
		return new Charcoal_File( s($this->_tmp_name) );
	}

	/*
	 *  オリジナルファイル名の拡張子を取得
	 *
	 */
	public function getExtension()
	{
		$pos = strrpos( $this->_name, '.' );
		if ( is_int($pos) ){
			return substr( $this->_name, $pos );
		}
		return '';
	}

	/*
	 *  エラーチェック
	 *
	 */
	public function checkErrors()
	{
		switch( $this->_error ){
		case UPLOAD_ERR_OK:
			log_info( "system,debug", "UPLOAD_ERR_OK" );
			break;
		case UPLOAD_ERR_INI_SIZE:
			log_info( "system,debug", "UPLOAD_ERR_INI_SIZE" );
			_throw( new Charcoal_FileUploadIniSizeException($this) );
			break;
		case UPLOAD_ERR_FORM_SIZE:
			log_info( "system,debug", "UPLOAD_ERR_FORM_SIZE" );
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
			log_warning( "system,debug", "unexpected upload error:" . $this->_error );
			break;
		}
	}

}

return __FILE__;
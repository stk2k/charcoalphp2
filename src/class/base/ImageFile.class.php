<?php
/**
* 画像クラス
*
* PHP version 5
*
* @package    class.base
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ImageFile extends Charcoal_File
{
	private $_data;

	/*
	 *    コンストラクタ
	 */
	public function __construct( $path, $parent = NULL )
	{
		Charcoal_ParamTrait::validateString( 1, $path );
		Charcoal_ParamTrait::validateIsA( 2, 'Charcoal_File', $parent, TRUE );

		parent::__construct( $path, $parent );

		if ( !is_readable(parent::getPath() ) ){
			_throw( new Charcoal_FileNotFoundException($this) );
		}

		$this->_data = getimagesize( parent::getPath() );
		if ( $this->_data === FALSE ){
			_throw( new Charcoal_ImageGetSizeException( $this ) );
		}
	}

	/*
	 *	画像の幅
	 */
	public function getImageWidth()
	{
		return i($this->_data[0]);
	}

	/*
	 *	画像の高さ
	 */
	public function getImageHeight()
	{
		return i($this->_data[1]);
	}

	/*
	 *	画像タイプ
	 */
	public function getImageType()
	{
		return i($this->_data[2]);
	}

	/*
	 *	ビット／ピクセル数
	 */
	public function getImageBits()
	{
		$bits = isset($this->_data['bits']) ? $this->_data['bits'] : 0;
		return i($bits);
	}

	/*
	 *	チャンネル数
	 */
	public function getImageChannels()
	{
		$bits = isset($this->_data['channels']) ? $this->_data['channels'] : 0;
		return i($bits);
	}

	/*
	 *	MIMEタイプ
	 */
	public function getImageMime()
	{
		$mime = isset($this->_data['mime']) ? $this->_data['mime'] : '';
		return s($mime);
	}
}


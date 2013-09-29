<?php
/**
* Binary file output class
*
* PHP version 5
*
* @package    classes.io
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_FileWriter
{
	private $file;
	private $mode;
	private $fp;

	/**
	 * Construct object
	 *
	 * Charcoal_File $pattern      shell wildcard pattern
	 * string $mode                shell wildcard pattern
	 */
	public function __construct( $file, $mode = NULL )
	{
//		Charcoal_ParamTrait::checkFile( 1, $pattern );
//		Charcoal_ParamTrait::checkString( 2, $extension, TRUE );

		$this->file = $file;
		$this->mode = $mode ? $mode : 'w';
		$this->fp = NULL;
	}

	/**
	 *  Open file
	 *
	 * @param Charcoal_String $mode File access mode
	 *
	 * @return void 
	 */
	private function open( Charcoal_String $mode = NULL )
	{
		$path = $this->file->getPath();

		$this->fp = @fopen($path,$this->mode);
		if ( !$this->fp ){
			_throw( new Charcoal_FileOpenException( $path ) );
		}
	}

	/**
	 *  Close file
	 *
	 * @return void 
	 */
	private function close()
	{
		if ( $this->fp ){
			fclose($this->fp);
		}
		$this->fp = NULL;
	}

	/**
	 *  Write file
	 *
	 * @param Charcoal_String $mode File access mode
	 *
	 * @return Charcoal_Integer Written size in bytes
	 */
	public function write( $data )
	{
		if ( !$this->fp ){
			_throw( new Charcoal_FileOutputException( $this->file->getPath() ) );
		}
		$ret = fwrite($this->fp, $data);
		if ( $ret === FALSE ){
			_throw( new Charcoal_FileOutputException( $this->file->getPath() ) );
		}
		return i($ret);
	}

}



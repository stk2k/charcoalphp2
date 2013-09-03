<?php
/**
* File Class
*
* PHP version 5
*
* @package    filters
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_File extends Charcoal_Object
{
	private $_path;

	/**
	 *	Construct object
	 *
	 * @param Charcoal_String $file_name    Name of the file or directory.
	 * @param Charcoal_File $parent    Parent object
	 */
	public function __construct( Charcoal_String $file_name, Charcoal_File $parent = NULL )
	{
		parent::__construct();

		$path = $parent ? $parent->getPath() . DIRECTORY_SEPARATOR . us($file_name) : us($file_name);

		while( stripos($path,'//') !== FALSE ){
			$path = str_replace('//','/',$path);
		}

		$this->_path = $path;
	}

	/**
	 *  Create object
	 */
	public static function create( Charcoal_String $file_name )
	{
		return new Charcoal_File( $file_name );
	}

	/**
	 *  Returns if the file or directory can be read.
	 *
	 * @return bool TRUE if the file or directory can be read.
	 */
	public function canRead()
	{
		return is_readable($this->_path);
	}

	/**
	 *  Returns if the file or directory can be written.
	 *
	 * @return bool TRUE if the file or directory can be written.
	 */
	public function canWrite()
	{
		return is_writeable( $this->_path );
	}

	/**
	 *  Returns file size of the file or directory in bytes.
	 *
	 * @return int size of the file or directory in bytes.
	 */
	public function getFileSize()
	{
		return filesize( $this->_path );
	}

	/**
	 *  Delete the file or directory
	 *
	 * @return bool TRUE if the file or directory is successfully deleted, otherwise FALSE.
	 */
	public function delete()
	{
		return unlink( $this->_path );
	}

	/**
	 *  Virtual path
	 *
	 * @return Charcoal_String 
	 */
	public function getPath()
	{
		return s( $this->_path );
	}

	/**
	 *  Return if the path means file.
	 *
	 * @return bool TRUE if path means file, otherwise FALSE. 
	 */
	public function isFile()
	{
		return is_file( $this->_path );
	}

	/**
	 *  Return if the path means directory.
	 *
	 * @return bool TRUE if path means directory, otherwise FALSE. 
	 */
	public function isDir()
	{
		return is_dir( $this->_path );
	}

	/**
	 *  Return if the path means directory.
	 *
	 * @return bool TRUE if path means directory, otherwise FALSE. 
	 */
	public function isDirectory()
	{
		return is_dir( $this->_path );
	}

	/**
	 *  Return if the file or directory can be read.
	 *
	 * @return bool TRUE if the file or directory can be read, otherwise FALSE. 
	 */
	public function isReadable()
	{
		return is_readable( $this->_path );
	}

	/**
	 *  Return if the file or directory can be written.
	 *
	 * @return bool TRUE if the file or directory can be written, otherwise FALSE. 
	 */
	public function isWriteable()
	{
		return is_writable( $this->_path );
	}

	/**
	 *  Extension of the file
	 *
	 * @return Charcoal_String Extension of the file
	 */
	public function getExtension()
	{
		return pathinfo( $this->_path, PATHINFO_EXTENSION );
	}

	/**
	 *  Return if the file or directory exists.
	 *
	 * @return bool TRUE if the file or directory exists, otherwise FALSE. 
	 */
	public function exists()
	{
		return file_exists( $this->_path );
	}

	/**
	 *  Absolute path of the file or directory
	 *
	 * @return Charcoal_String
	 */
	public function getAbsolutePath()
	{
		return realpath( $this->_path );
	}

	/**
	 *  Name of the file or directory
	 *
	 * Charcoal_String $suffix       file suffix which is ignored.
	 *
	 * @return Charcoal_String
	 */
	public function getName( Charcoal_String $suffix = NULL )
	{
		$name = basename( $this->_path, $suffix );

		return s($name);
	}

	/**
	 *  Parent directory
	 *
	 * @return Charcoal_File
	 */
	public function getDir()
	{
		return new Charcoal_File( s(dirname($this->_path)) );
	}

	/**
	 *  Name of parent directory
	 *
	 * @return Charcoal_String
	 */
	public function getDirName()
	{
		return s(dirname( $this->_path ));
	}

	/**
	 *  Child of the file or directory
	 *
	 * @return Charcoal_File
	 */
	public function getChild( Charcoal_String $file_or_dir_name )
	{
		return new Charcoal_File( s($this->_path . DIRECTORY_SEPARATOR . us($file_or_dir_name)) );
	}

	/**
	 *  Contents of the file or directory
	 *
	 * @return Charcoal_File
	 */
	public function getContents()
	{
		return file_get_contents( $this->_path );
	}

	/**
	 *  Save string data as a file
	 *
	 * @return Charcoal_File
	 */
	public function putContents( Charcoal_String $contents )
	{
		return file_put_contents( $this->_path, us($contents) );
	}

	/**
	 *  Rename the file or directory
	 */
	public function rename( Charcoal_File $new_file)
	{
		$res = rename( $this->_path, $new_file->getPath() );
		if ( $res === FALSE ){
			_throw( new Charcoal_FileRenameException($this,$new_file) );
		}
	}

	/**
	 *  Create file
	 *
	 * @param Charcoal_String $mode File mode
	 * @param Charcoal_String $contents File contents
	 * @param Charcoal_Boolean $drilldown If TRUE, all of parent directory may be created automatically.
	 *
	 * @return void
	 */
	public function makeFile( Charcoal_String $mode, Charcoal_String $contents, Charcoal_Boolean $drilldown = NULL )
	{
		$parent_dir = $this->getDir();

		if ( $drilldown === NULL ){
			$drilldown = b(TRUE);
		}

		$parent_dir->makeDirectory($mode, $drilldown);

		$path = $this->_path;

		$ret = file_put_contents( $path, $contents );
		if ( $ret === FALSE ){
			_throw( new Charcoal_MakeFileException(s($path)) );
		}
	}

	/**
	 *  Create empty directory
	 *
	 * @param Charcoal_String $mode File mode
	 * @param Charcoal_Boolean $drilldown If TRUE, all of parent directory may be created automatically.
	 *
	 * @return void
	 */
	public function makeDirectory( Charcoal_String $mode, Charcoal_Boolean $drilldown = NULL )
	{
		$drilldown = $drilldown ? $drilldown->isTrue() : FALSE;

		$path = $this->_path;

		if ( file_exists($path) )	return;

		$res = mkdir( us($path), us($mode), $drilldown );
		if ( $res === FALSE ){
			$msg = "mkdir failed: $path";
			_throw( new Charcoal_MakeDirectoryException(s($msg)) );
		}
	}

	/**
	 *  Listing up files in directory which this object means
	 *
	 * @param Charcoal_IFileFilter $filter       Fileter object which implements selection logic. If this parameter is omitted, all files will be selected.
	 *
	 * @return array of Charcoal_File            File objects which are found by the filter. If it fails, NULL is returned.
	 */
	public function listFiles( Charcoal_IFileFilter $filter = NULL )
	{
		$path = $this->_path;

		if ( !file_exists($path) )	return NULL;

		if ( !is_readable($path) )	return NULL;

		if ( is_file($path) )	return NULL;

		$files = array();

		$dh = opendir($path);
		while( ($file_name = readdir($dh)) !== FALSE ){
			$file = new Charcoal_File( s($file_name) );
			if ( $filter ){
				if ( $filter->accept($file) ){
					$files[] = $file;
				}
			}
			else{
				$files[] = $file;
			}
		}
		return $files;
	}


	/**
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return $this->_path;
	}
}


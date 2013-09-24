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
	public function __construct( $file_name, $parent = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $file_name );
//		Charcoal_ParamTrait::checkFile( 2, $parent, TRUE );

		parent::__construct();

		$path = $parent ? $parent->getPath() . '/' . $file_name : $file_name;

		while( stripos($path,'//') !== FALSE ){
			$path = str_replace('//','/',$path);
		}

		$this->_path = $path;
	}

	/**
	 *  Create file object
	 *
	 * @param Charcoal_String $file_name    Name of the file or directory.
	 */
	public static function create( $file_name )
	{
//		Charcoal_ParamTrait::checkString( 1, $file_name );

		return new Charcoal_File( $file_name );
	}

	/**
	 *  Returns if the file or directory can be read.
	 *
	 * @return bool TRUE if the file or directory can be read.
	 */
	public function canRead()
	{
		return is_readable( $this->_path );
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
		return $this->_path;
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
	 *  returns last modified time(UNIX time)
	 *
	 * @return int    UNIX time
	 */
	public function getLastModified()
	{
		return getlastmod( $this->_path );
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
	public function getName( $suffix = NULL )
	{
		$name = basename( $this->_path, $suffix );

		return $name;
	}

	/**
	 *  Parent directory
	 *
	 * @return Charcoal_File
	 */
	public function getDir()
	{
		return new Charcoal_File( dirname($this->_path) );
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
	public function getChild( $file_or_dir_name )
	{
		return new Charcoal_File( $this->_path . DIRECTORY_SEPARATOR . $file_or_dir_name );
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
	public function putContents( $contents )
	{
		return file_put_contents( $this->_path, $contents );
	}

	/**
	 *  Rename the file or directory
	 */
	public function rename( $new_file )
	{
		$res = rename( $this->_path, $new_file->getPath() );
		if ( $res === FALSE ){
			_throw( new Charcoal_FileRenameException( $this->getPath() ,$new_file->getPath() ) );
		}
	}

	/**
	 *  Create file
	 *
	 * @param string $mode File mode
	 * @param string $contents File contents
	 * @param bool $drilldown If TRUE, all of parent directory may be created automatically.
	 *
	 * @return void
	 */
	public function makeFile( $mode, $contents, $drilldown = TRUE )
	{
//		Charcoal_ParamTrait::checkString( 1, $mode );
//		Charcoal_ParamTrait::checkString( 2, $contents );
//		Charcoal_ParamTrait::checkBool( 3, $drilldown );

		$parent_dir = $this->getDir();

		$parent_dir->makeDirectory( $mode, $drilldown );

		$path = $this->_path;

		$ret = file_put_contents( $path, $contents );
		if ( $ret === FALSE ){
			_throw( new Charcoal_MakeFileException( $path ) );
		}
	}

	/**
	 *  Create empty directory
	 *
	 * @param string $mode File mode
	 * @param bool $drilldown If TRUE, all of parent directory may be created automatically.
	 *
	 * @return void
	 */
	public function makeDirectory( $mode, $drilldown = TRUE )
	{
//		Charcoal_ParamTrait::checkString( 1, $mode );
//		Charcoal_ParamTrait::checkBool( 2, $drilldown );

		$path = $this->_path;
		$mode = us( $mode );
		$drilldown = ub( $drilldown );

		if ( file_exists($path) )	return;

		$res = mkdir( $path, $mode, $drilldown );
		if ( $res === FALSE ){
			_throw( new Charcoal_MakeDirectoryException( $path ) );
		}
	}

	/**
	 *  Listing up files in directory which this object means
	 *
	 * @param Charcoal_IFileFilter $filter       Fileter object which implements selection logic. If this parameter is omitted, all files will be selected.
	 *
	 * @return array of Charcoal_File            File objects which are found by the filter. If it fails, NULL is returned.
	 */
	public function listFiles( $filter = NULL )
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


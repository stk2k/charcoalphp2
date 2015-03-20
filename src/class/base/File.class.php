<?php
/**
* File Class
*
* PHP version 5
*
* @package    class.base
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_File extends Charcoal_Object
{
	private $path;

	/**
	 *	Construct object
	 *
	 * @param Charcoal_String $file_name    Name of the file or directory.
	 * @param Charcoal_File $parent    Parent object
	 */
	public function __construct( $file_name, $parent = NULL )
	{
//		Charcoal_ParamTrait::validateString( 1, $file_name );
//		Charcoal_ParamTrait::validateFile( 2, $parent, TRUE );

		parent::__construct();

		$path = $parent ? $parent->getPath() . '/' . us($file_name) : us($file_name);

		$this->path = str_replace('//','/',$path);
	}

	/**
	 *  Create file object
	 *
	 * @param Charcoal_String $file_name    Name of the file or directory.
	 */
	public static function create( $file_name )
	{
//		Charcoal_ParamTrait::validateString( 1, $file_name );

		return new Charcoal_File( $file_name );
	}

	/**
	 *  Returns if the file or directory can be read.
	 *
	 * @return bool TRUE if the file or directory can be read.
	 */
	public function canRead()
	{
		return is_readable( $this->path );
	}

	/**
	 *  Returns if the file or directory can be written.
	 *
	 * @return bool TRUE if the file or directory can be written.
	 */
	public function canWrite()
	{
		return is_writeable( $this->path );
	}

	/**
	 *  Returns file size of the file or directory in bytes.
	 *
	 * @return int size of the file or directory in bytes.
	 */
	public function getFileSize()
	{
		return filesize( $this->path );
	}

	/**
	 *  Delete the file or directory
	 *
	 * @return bool TRUE if the file or directory is successfully deleted, otherwise FALSE.
	 */
	public function delete()
	{
		return unlink( $this->path );
	}

	/**
	 *  Virtual path
	 *
	 * @return Charcoal_String 
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 *  Return if the path means file.
	 *
	 * @return bool TRUE if path means file, otherwise FALSE. 
	 */
	public function isFile()
	{
		return is_file( $this->path );
	}

	/**
	 *  Return if the path means directory.
	 *
	 * @return bool TRUE if path means directory, otherwise FALSE. 
	 */
	public function isDir()
	{
		return is_dir( $this->path );
	}

	/**
	 *  Return if the path means directory.
	 *
	 * @return bool TRUE if path means directory, otherwise FALSE. 
	 */
	public function isDirectory()
	{
		return is_dir( $this->path );
	}

	/**
	 *  Return if the file or directory can be read.
	 *
	 * @return bool TRUE if the file or directory can be read, otherwise FALSE. 
	 */
	public function isReadable()
	{
		return is_readable( $this->path );
	}

	/**
	 *  Return if the file or directory can be written.
	 *
	 * @return bool TRUE if the file or directory can be written, otherwise FALSE. 
	 */
	public function isWriteable()
	{
		return is_writable( $this->path );
	}

	/**
	 *  Extension of the file
	 *
	 * @return Charcoal_String Extension of the file
	 */
	public function getExtension()
	{
		return pathinfo( $this->path, PATHINFO_EXTENSION );
	}

	/**
	 *  returns last modified time(UNIX time)
	 *
	 * @return int    UNIX time
	 */
	public function getLastModified()
	{
		return getlastmod( $this->path );
	}

	/**
	 *  Return if the file or directory exists.
	 *
	 * @return bool TRUE if the file or directory exists, otherwise FALSE. 
	 */
	public function exists()
	{
		return file_exists( $this->path );
	}

	/**
	 *  Absolute path of the file or directory
	 *
	 * @return Charcoal_String
	 */
	public function getAbsolutePath()
	{
		return realpath( $this->path );
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
		$name = basename( $this->path, $suffix );

		return $name;
	}

	/**
	 *  Parent directory
	 *
	 * @return Charcoal_File
	 */
	public function getDir()
	{
		return new Charcoal_File( dirname($this->path) );
	}

	/**
	 *  Name of parent directory
	 *
	 * @return Charcoal_String
	 */
	public function getDirName()
	{
		return s(dirname( $this->path ));
	}

	/**
	 *  Child of the file or directory
	 *
	 * @return Charcoal_File
	 */
	public function getChild( $file_or_dir_name )
	{
		return new Charcoal_File( $this->path . DIRECTORY_SEPARATOR . $file_or_dir_name );
	}

	/**
	 *  Contents of the file or directory
	 *
	 * @return Charcoal_File
	 */
	public function getContents()
	{
		return file_get_contents( $this->path );
	}

	/**
	 *  get contents of the file as array
	 *
	 * @return array           file contents
	 */
	public function getContentsAsArray()
	{
		return file( $this->path );
	}

	/**
	 *  Save string data as a file
	 *
	 * @return Charcoal_File
	 */
	public function putContents( $contents )
	{
		return file_put_contents( $this->path, $contents, LOCK_EX );
	}

	/**
	 *  Rename the file or directory
	 */
	public function rename( $new_file )
	{
		$res = rename( $this->path, $new_file->getPath() );
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
//		Charcoal_ParamTrait::validateString( 1, $mode );
//		Charcoal_ParamTrait::validateString( 2, $contents );
//		Charcoal_ParamTrait::checkBool( 3, $drilldown );

		$parent_dir = $this->getDir();

		$parent_dir->makeDirectory( $mode, $drilldown );

		$path = $this->path;

		$ret = file_put_contents( $path, $contents );
		if ( $ret === FALSE ){
			_throw( new Charcoal_MakeFileException( $path ) );
		}
	}

	/**
	 *  Create empty directory
	 *
	 * @param string $mode                  File mode.If this parameter is set NULL, 0777 will be applied.
	 * @param bool $drilldown               If TRUE, all of parent directory may be created automatically.
	 *
	 * @return void
	 */
	public function makeDirectory( $drilldown = TRUE, $mode = NULL )
	{
//		Charcoal_ParamTrait::validateBoolean( 1, $drilldown );
//		Charcoal_ParamTrait::validateInteger( 2, $mode, TRUE );

		$path = $this->path;
		$mode = $mode ? ui( $mode ) : 0777;
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
		$path = $this->path;

		if ( !file_exists($path) )	return NULL;

		if ( !is_readable($path) )	return NULL;

		if ( is_file($path) )	return NULL;

		$files = array();

		$dh = opendir($path);
		while( ($file_name = readdir($dh)) !== FALSE ){
			if ( $file_name === '.' || $file_name === '..' ){
				continue;
			}
			$file = new Charcoal_File( $file_name, $this );
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
	 *  Update last modified date of the file
	 *
	 * @param integer|Charcoal_Integer $time      time value to set
	 *
	 * @return boolean   TRUE if success, FALSE if failed
	 */
	public function touch( $time = NULL )
	{
		if ( $time === NULL ){
			return touch( $this->path );
		}
		return touch( $this->path, ui($time) );
	}

	/**
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return $this->path;
	}
}


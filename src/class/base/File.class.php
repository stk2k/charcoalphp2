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
     *    Construct object
     *
     * @param Charcoal_String|string $file_name    Name of the file or directory.
     * @param Charcoal_File $parent    Parent object
     */
    public function __construct( $file_name, $parent = NULL )
    {
//        Charcoal_ParamTrait::validateString( 1, $file_name );
//        Charcoal_ParamTrait::validateFile( 2, $parent, TRUE );

        assert( $file_name instanceof Charcoal_String || is_string($file_name), 'Paremeter 1 must be string or Charcoal_String' );
        assert( $parent instanceof Charcoal_File || $parent === NULL, 'Paremeter 2 must be NULL or Charcoal_File' );

        parent::__construct();

        $path = $parent ? $parent->getPath() . '/' . us($file_name) : us($file_name);

        $this->path = str_replace('//','/',$path);
    }

    /**
     *  Create file object
     *
     * @param Charcoal_String|string $file_name    Name of the file or directory.
     *
     * @return Charcoal_File
     */
    public static function create( $file_name )
    {
//        Charcoal_ParamTrait::validateString( 1, $file_name );

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
     * @param bool $drilldown If TRUE, all of child directory may be removed automatically.
     *
     * @return bool TRUE if the file or directory is successfully deleted, otherwise FALSE.
     */
    public function delete( $drilldown = FALSE )
    {
        if ( is_file( $this->path ) ){
            return @unlink( $this->path );
        }
        if ( $drilldown ){
            return self::removeDirectoryRecursive( $this->path );
        }
        return @rmdir( $this->path );
    }

    /**
     *  Delete the file or directory
     *
     * @param string $path                    directory path to remove
     *
     * @return bool TRUE if the file or directory is successfully deleted, otherwise FALSE.
     */
    private static function removeDirectoryRecursive( $path )
    {
        if ( !file_exists($path) ){
            //if ( CHARCOAL_RUNMODE != 'http' ) echo "file_exists failed: $path" . PHP_EOL;
            return FALSE;
        }

        $handle = opendir("$path");
        if ( $handle === FALSE ) {
            //if ( CHARCOAL_RUNMODE != 'http' ) echo "opendir failed: $path" . PHP_EOL;
            return FALSE;
        }
        while ( false !== ($item = readdir($handle)) ) {
            if ($item != "." && $item != "..") {
                if (is_dir("$path/$item")) {
                    self::removeDirectoryRecursive( "$path/$item" );
                } else {
                    unlink( "$path/$item" );
                    //if ( CHARCOAL_RUNMODE != 'http' ) echo "unlink: $path/$item ret=$ret" . PHP_EOL;
                }
            }
        }
        closedir( $handle );
        $ret = @rmdir( $path );
        //if ( CHARCOAL_RUNMODE != 'http' ) echo "rmdir: $path ret=$ret" . PHP_EOL;
        return $ret;
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
        return filemtime( $this->path );
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
     * @param Charcoal_String|string|NULL $suffix       file suffix which is ignored.
     *
     * @return Charcoal_String
     */
    public function getName( $suffix = NULL )
    {
        $name = $suffix ? basename( $this->path, $suffix ) : basename( $this->path );

        return $name;
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
     * @param Charcoal_String|string $file_or_dir_name
     *
     * @return Charcoal_File
     */
    public function getChild( $file_or_dir_name )
    {
        return new Charcoal_File( $this->path . DIRECTORY_SEPARATOR . $file_or_dir_name );
    }

    /**
     *  Parent of the file or directory
     *
     * @return Charcoal_File
     */
    public function getParent()
    {
        return new Charcoal_File( dirname($this->path) );
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
     * @param Charcoal_String|string $contents
     *
     * @return Charcoal_File
     */
    public function putContents( $contents )
    {
        return file_put_contents( $this->path, $contents, LOCK_EX );
    }

    /**
     *  Rename the file or directory
     *
     * @param Charcoal_File $new_file
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
     *
     * @return void
     */
    public function makeFile( $mode, $contents )
    {
//        Charcoal_ParamTrait::validateString( 1, $mode );
//        Charcoal_ParamTrait::validateString( 2, $contents );

        $parent_dir = $this->getParent();

        $parent_dir->makeDirectory( $mode );

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
     *
     * @return void
     */
    public function makeDirectory( $mode = NULL )
    {
//        Charcoal_ParamTrait::validateInteger( 2, $mode, TRUE );

        $path = $this->path;
        $mode = $mode ? ui( $mode ) : 0777;

        if ( file_exists($path) ){
            if ( is_file($path) ){
                _throw( new Charcoal_MakeDirectoryException( $path ) );
            }
            return;
        }

        $parent_dir = $this->getParent();

        if ( !$parent_dir->exists() ){
            $parent_dir->makeDirectory( $mode );
        }

        $res = mkdir( $path, $mode );
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

        if ( !file_exists($path) )    return NULL;

        if ( !is_readable($path) )    return NULL;

        if ( is_file($path) )    return NULL;

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


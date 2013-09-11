<?php
/**
* File System Component
*
* PHP version 5
*
* @package    components.charcoal.http
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/
require_once( 'FileSystemComponentException' . CHARCOAL_CLASS_FILE_SUFFIX );

class Charcoal_FileSystemComponent extends Charcoal_CharcoalComponent implements Charcoal_IComponent
{
	private $_base_dir;
	private $_base_dir_obj;

	/*
	 *	Construct object
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		$this->_base_dir    = $config->getString( s('base_dir'), s(CHARCOAL_BASE_DIR) )->getValue();

		$this->_base_dir_obj    = new Charcoal_File( s($this->_base_dir) );
	}

	/*
	 * create directory
	 *
	 * @return Charcoal_File file object of created directory
	 */
	public function createDirectory( Charcoal_String $dir_path, Charcoal_String $mode )
	{
		try{
			$obj = new Charcoal_File( s($dir_path), $this->_base_dir_obj );

			$obj->makeDirectory( $mode, b(TRUE) );

			return $obj;
		}
		catch( Exception $e )
		{
			_catch( $e );

			_throw( new Charcoal_FileSystemComponentException( s('creating directory failed.'), $e ) );
		}
	}

	/*
	 * create file
	 *
	 * @return Charcoal_File file object of created file
	 */
	public function createFile( Charcoal_String $file_path, Charcoal_String $mode, Charcoal_String $contents, Charcoal_Boolean $overwrite = NULL )
	{
		if ( $overwrite == NULL ){
			$overwrite = b(TRUE);
		}

		$obj = new Charcoal_File( s($file_path), $this->_base_dir_obj );

		if ( $overwrite->isTrue() ){
			if ( $obj->exists() && !$obj->canWrite() ){
				_throw( new Charcoal_FileSystemComponentException( s('specified file is not writeable.') ) );
			}
		}
		else if ( $obj->exists() ){
			_throw( new Charcoal_FileSystemComponentException( s('specified file is already exists.') ) );
		}

		try{
			// create file with parent directory
			$obj->makeFile( $mode, $contents, b(TRUE) );

			return $obj;
		}
		catch( Exception $e )
		{
			_catch( $e );

			_throw( new Charcoal_FileSystemComponentException( s('creating file failed.'), $e ) );
		}
	}
}


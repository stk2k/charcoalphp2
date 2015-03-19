<?php
/**
* File System Component
*
* PHP version 5
*
* @package    component.charcoal.file
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
require_once( 'FileSystemComponentException.class.php' );

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
	 * @return Charcoal_String|string $dir_path      directory path to create
	 * @return Charcoal_Integer|integer $mode        directory creation mode
	 *
	 * @return Charcoal_File file object of created directory
	 */
	public function createDirectory( $dir_path, $mode = 0777 )
	{
		Charcoal_ParamTrait::validateString( 1, $dir_path );
		Charcoal_ParamTrait::validateInteger( 2, $mode );

		try{
			$obj = new Charcoal_File( $dir_path, $this->_base_dir_obj );

			$obj->makeDirectory( $mode, TRUE );

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
	 * @return Charcoal_String|string $file_path      file path to create
	 * @return Charcoal_Integer|integer $mode         file creation mode
	 * @return Charcoal_String|string $contents       file contents
	 * @return Charcoal_Boolean|bool $overwrite       If true, existing file will be overwrited by the new file.
	 *
	 * @return Charcoal_File file object of created file
	 */
	public function createFile( $file_path, $contents, $overwrite = TRUE, $mode = 0777 )
	{
		Charcoal_ParamTrait::validateString( 1, $file_path );
		Charcoal_ParamTrait::validateString( 2, $contents );
		Charcoal_ParamTrait::validateBoolean( 3, $overwrite );
		Charcoal_ParamTrait::validateInteger( 4, $mode );

		$obj = new Charcoal_File( $file_path, $this->_base_dir_obj );

		if ( $overwrite ){
			if ( $obj->exists() && !$obj->canWrite() ){
				_throw( new Charcoal_FileSystemComponentException( 'specified file is not writeable.' ) );
			}
		}
		elseif ( $obj->exists() ){
			_throw( new Charcoal_FileSystemComponentException( 'specified file is already exists.' ) );
		}

		try{
			// create file with parent directory
			$obj->makeFile( $mode, $contents, TRUE );

			return $obj;
		}
		catch( Exception $e )
		{
			_catch( $e );

			_throw( new Charcoal_FileSystemComponentException( s('creating file failed.'), $e ) );
		}
	}
}


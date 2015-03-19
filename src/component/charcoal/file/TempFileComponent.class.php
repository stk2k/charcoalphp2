<?php
/**
* Temporary File Component
*
* PHP version 5
*
* @package    component.charcoal.file
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
require_once( 'TempFileComponentException.class.php' );

class Charcoal_TempFileComponent extends Charcoal_CharcoalComponent implements Charcoal_IComponent
{
	private $base_root;
	private $mode;
	private $overwrite;
	private $contents;
	private $parent_dir;
	private $file_name;

	/**
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

		$this->base_root  = us( $config->getString( 'base_root', CHARCOAL_BASE_DIR, TRUE ) );
		$this->mode       = us( $config->getString( 'mode', '777' ) );
		$this->overwrite  = ub( $config->getBoolean( 'overwrite', TRUE ) );
		$this->parent_dir = us( $config->getString( 'parent_dir' ) );
	}

	/**
	 * Set temporary file contents
	 *
	 * @param string|Charcoal_String $contents file contents
	 */
	public function setContents( $contents )
	{
		Charcoal_ParamTrait::validateString( 1, $contents );

		$this->contents = us($contents);
	}

	/**
	 * Get temporary file contents
	 *
	 * @return string
	 */
	public function getContents()
	{
		return $this->contents;
	}

	/**
	 * Set temporary file mode
	 *
	 * @param string|Charcoal_String $mode file mode
	 */
	public function setMode( $mode )
	{
		Charcoal_ParamTrait::validateString( 1, $mode );

		$this->mode = us($mode);
	}

	/**
	 * Get temporary file mode
	 *
	 * @return string
	 */
	public function getMode()
	{
		return $this->mode;
	}

	/**
	 * Set parent directory path
	 *
	 * @param string|Charcoal_String $path parent directory path
	 */
	public function setParentDir( $parent_dir )
	{
		Charcoal_ParamTrait::validateString( 1, $parent_dir );

		$this->parent_dir = us($parent_dir);
	}

	/**
	 * Get parent directory path
	 *
	 * @return string
	 */
	public function getParentDir()
	{
		return $this->parent_dir;
	}

	/**
	 * Set file name
	 *
	 * @param string|Charcoal_String $file_name file name
	 */
	public function setFileName( $file_name )
	{
		Charcoal_ParamTrait::validateString( 1, $file_name );

		$this->file_name = us($file_name);
	}

	/**
	 * Get file name
	 *
	 * @return string
	 */
	public function getFileName()
	{
		return $this->file_name;
	}

	/**
	 * Set overwrite mode
	 *
	 * @param bool|Charcoal_Boolean $overwrite TRUE if the temporary file should be overwritten, FALSE otherwise.
	 */
	public function setOverwrite( $overwrite )
	{
		Charcoal_ParamTrait::validateBoolean( 1, $file_name );

		$this->overwrite = ub($overwrite);
	}

	/**
	 * Get overwrite mode
	 *
	 * @return bool TRUE if the temporary file should be overwritten, FALSE otherwise.
	 */
	public function isOverwrite()
	{
		return $this->overwrite;
	}

	/**
	 * create file
	 *
	 * @return Charcoal_File file object of created file
	 */
	public function create()
	{
		$obj = Charcoal_File::create( s($this->base_root) )->getChild( s($this->parent_dir) )->getChild( s($this->file_name) );

		if ( $this->isOverwrite() ){
			if ( $obj->exists() && !$obj->canWrite() ){
				_throw( new Charcoal_FileSystemComponentException( s('specified file is not writeable.') ) );
			}
		}
		elseif ( $obj->exists() ){
			_throw( new Charcoal_FileSystemComponentException( s('specified file is already exists.') ) );
		}

		try{
			// create file with parent directory
			$obj->makeFile( $this->mode, $this->contents, TRUE );

			return $obj;
		}
		catch( Exception $e )
		{
			_catch( $e );

			_throw( new Charcoal_TempFileComponentException( s('creating file failed.'), $e ) );
		}
	}
}


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
	private $_base_root;
	private $_mode;
	private $_overwrite;
	private $_contents;
	private $_parent_dir;
	private $_file_name;

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

		$this->_base_root  = $config->getString( s('base_root'), s(CHARCOAL_BASE_DIR) )->getValue();
		$this->_mode       = $config->getString( 'mode', '777' )->getValue();
		$this->_overwrite  = $config->getBoolean( 'overwrite', TRUE )->getValue();
		$this->_parent_dir = $config->getString( s('parent_dir') )->getValue();
	}

	/**
	 * Set temporary file contents
	 *
	 * @param Charcoal_String $contents file contents
	 */
	public function setContents( Charcoal_String $contents )
	{
		$this->_contents = us($contents);
	}

	/**
	 * Get temporary file contents
	 *
	 * @return string
	 */
	public function getContents()
	{
		return $this->_contents;
	}

	/**
	 * Set temporary file mode
	 *
	 * @param Charcoal_String $mode file mode
	 */
	public function setMode( Charcoal_String $mode )
	{
		$this->_mode = us($mode);
	}

	/**
	 * Get temporary file mode
	 *
	 * @return string
	 */
	public function getMode()
	{
		return $this->_mode;
	}

	/**
	 * Set parent directory path
	 *
	 * @param Charcoal_String $path parent directory path
	 */
	public function setParentDir( Charcoal_String $parent_dir )
	{
		$this->_parent_dir = us($parent_dir);
	}

	/**
	 * Get parent directory path
	 *
	 * @return string
	 */
	public function getParentDir()
	{
		return $this->_parent_dir;
	}

	/**
	 * Set file name
	 *
	 * @param Charcoal_String $file_name file name
	 */
	public function setFileName( Charcoal_String $file_name )
	{
		$this->_file_name = us($file_name);
	}

	/**
	 * Get file name
	 *
	 * @return string
	 */
	public function getFileName()
	{
		return $this->_file_name;
	}

	/**
	 * Set overwrite mode
	 *
	 * @param Charcoal_Boolean $overwrite TRUE if the temporary file should be overwritten, FALSE otherwise.
	 */
	public function setOverwrite( Charcoal_Boolean $overwrite )
	{
		$this->_overwrite = ub($overwrite);
	}

	/**
	 * Get overwrite mode
	 *
	 * @return bool TRUE if the temporary file should be overwritten, FALSE otherwise.
	 */
	public function isOverwrite()
	{
		return $this->_overwrite;
	}

	/**
	 * create file
	 *
	 * @return Charcoal_File file object of created file
	 */
	public function create()
	{
		$obj = Charcoal_File::create( s($this->_base_root) )->getChild( s($this->_parent_dir) )->getChild( s($this->_file_name) );

		if ( $this->isOverwrite() ){
			if ( $obj->exists() && !$obj->canWrite() ){
				_throw( new Charcoal_FileSystemComponentException( s('specified file is not writeable.') ) );
			}
		}
		else if ( $obj->exists() ){
			_throw( new Charcoal_FileSystemComponentException( s('specified file is already exists.') ) );
		}

		try{
			// create file with parent directory
			$obj->makeFile( $mode, s($this->_contents), b(TRUE) );

			return $obj;
		}
		catch( Exception $e )
		{
			_catch( $e );

			_throw( new Charcoal_TempFileComponentException( s('creating file failed.'), $e ) );
		}
	}
}


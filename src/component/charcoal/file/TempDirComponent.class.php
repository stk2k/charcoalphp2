<?php
/**
* Temporary Directory Component
*
* PHP version 5
*
* @package    component.charcoal.file
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
require_once( 'TempDirComponentException.class.php' );

class Charcoal_TempDirComponent extends Charcoal_CharcoalComponent implements Charcoal_IComponent
{
	private $_mode;
	private $_overwrite;
	private $_dir_path;
	private $_dir_name;

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

		$this->_mode       = $config->getString( 'mode', '777' )->getValue();
		$this->_overwrite  = $config->getBoolean( 'overwrite', TRUE )->getValue();
		$this->_dir_path   = $config->getString( s('dir_path'), s(CHARCOAL_BASE_DIR) )->getValue();
	}

	/**
	 * Set temporary file mode
	 *
	 * @param Charcoal_String $mode directory's file mode
	 */
	public function setMode( Charcoal_String $mode )
	{
		$this->_mode = us($mode);
	}

	/**
	 * Get temporary directory's file mode
	 *
	 * @return string
	 */
	public function getMode()
	{
		return $this->_mode;
	}

	/**
	 * Set temporary directory name
	 *
	 * @param Charcoal_Boolean $dir_name directory name
	 */
	public function setDirName( Charcoal_String $dir_name )
	{
		$this->_dir_name = us($dir_name);
	}

	/**
	 * Get temporary directory name
	 *
	 * @return string
	 */
	public function getDirName()
	{
		return $this->_dir_name;
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
	 * create directory
	 *
	 * @return Charcoal_File file object of created directory
	 */
	public function create()
	{
		try{
			$obj = new Charcoal_File( s($this->_dir_path) );

			$obj->makeDirectory( $this->_mode, b(TRUE) );

			return $obj;
		}
		catch( Exception $e )
		{
			_catch( $e );

			_throw( new Charcoal_TempDirComponentException( s('creating directory failed.'), $e ) );
		}
	}
}


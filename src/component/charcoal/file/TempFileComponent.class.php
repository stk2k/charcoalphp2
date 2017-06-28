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

class Charcoal_TempFileComponent extends Charcoal_CharcoalComponent implements Charcoal_ICharcoalComponent
{
    private $mode;
    private $overwrite;
    private $file;

    /**
     *    Construct object
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Initialize instance
     *
     * @param array $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );
    
        $config = new Charcoal_HashMap($config);

        $this->mode       = us( $config->getString( 'mode', '777' ) );
        $this->overwrite  = ub( $config->getBoolean( 'overwrite', TRUE ) );
    }

    /**
     * Set temporary file object
     *
     * @param Charcoal_File $file file object
     */
    public function setFile( $file )
    {
        Charcoal_ParamTrait::validateFile( 1, $file );

        $this->file = $file;
    }

    /**
     * Get temporary file object
     *
     * @return Charcoal_File|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set temporary file contents
     *
     * @param string|Charcoal_String $contents file contents
     */
    public function putContents( $contents )
    {
        Charcoal_ParamTrait::validateString( 1, $contents );

        /** @var Charcoal_File $file */
        $file = $this->file;
        if ( $file && $file->canWrite() ){
            $file->putContents( $contents );
        }
    }

    /**
     * Get temporary file contents
     *
     * @return string
     */
    public function getContents()
    {
        /** @var Charcoal_File $file */
        $file = $this->file;
        if ( $file && $file->canRead() ){
            return $file->getContents();
        }
        return '';
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
     * Set overwrite mode
     *
     * @param bool|Charcoal_Boolean $overwrite TRUE if the temporary file should be overwritten, FALSE otherwise.
     */
    public function setOverwrite( $overwrite )
    {
        Charcoal_ParamTrait::validateBoolean( 1, $overwrite );

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
     * @param string|Charcoal_String $contents
     * @param Charcoal_File $dir
     * @param string|Charcoal_String $file_name
     *
     * @return Charcoal_File
     */
    public function create( $contents, $dir = null, $file_name = null )
    {
        if ( $file_name === null ){
            $tmp_filename = Charcoal_System::hash() . '.tmp';
        }
        if ( $dir === null ){
            $dir = Charcoal_ResourceLocator::getFile( $this->getSandbox()->getEnvironment(), "%TMP_DIR%" );
        }

        $tmp_file = new Charcoal_File( $file_name, $dir );

        if ( $tmp_file->isDirectory() ){
            _throw( new Charcoal_FileSystemComponentException( 'specified path is directory.' ) );
        }
        if ( $tmp_file->exists() ){
            _throw( new Charcoal_FileSystemComponentException( 'specified file is already exists.' ) );
        }
        if ( $this->overwrite ){
            if ( $tmp_file->exists() && !$tmp_file->canWrite() ){
                _throw( new Charcoal_FileSystemComponentException( 'specified file is not writeable.' ) );
            }
        }

        try{
            // create file
            $tmp_file->makeFile( $this->mode, $contents, TRUE );

            $this->file = $tmp_file;

            return $tmp_file;
        }
        catch( Exception $e )
        {
            _catch( $e );

            _throw( new Charcoal_TempFileComponentException( s('creating file failed.'), $e ) );
        }
        return null;
    }
}


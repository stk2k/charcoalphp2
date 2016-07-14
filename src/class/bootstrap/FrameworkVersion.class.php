<?php
/**
*  Class for Framework Version Information
*
* PHP version 5
*
* @package    class.bootstrap
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
class Charcoal_FrameworkVersion extends Charcoal_Object
{
    const VERSION_MAJOR     = 2;
    const VERSION_MINOR     = 64;
    const VERSION_REVISION  = 0;
    const VERSION_BUILD     = 257;

    const VERSION_PART_ALL       = 0xFFFF;
    const VERSION_PART_MAJOR     = 0x0001;
    const VERSION_PART_MINOR     = 0x0002;
    const VERSION_PART_REVISION  = 0x0004;
    const VERSION_PART_BUILD     = 0x0008;

    const VERSION_STRING_SEPERATOR = '.';

    private $major;
    private $minor;
    private $revision;
    private $build;

    /**
     *    constructor
     */
    public function __construct()
    {
        $this->major = self::getVersion( i(self::VERSION_PART_MAJOR) );
        $this->minor = self::getVersion( i(self::VERSION_PART_MINOR) );
        $this->revision = self::getVersion( i(self::VERSION_PART_REVISION) );
        $this->build = self::getVersion( i(self::VERSION_PART_BUILD) );
    }

    /**
     *    get major version
     *
     *    @return int                     major version
     */
    public function getMajorVersion()
    {
        return $this->major;
    }

    /**
     *    get minor version
     *
     *    @return int                     minor version
     */
    public function getMinorVersion()
    {
        return $this->minor;
    }

    /**
     *    get revision number
     *
     *    @return int                     revision number
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     *    get build version
     *
     *    @return int                     build version
     */
    public function getBuildNumber()
    {
        return $this->build;
    }

    /*
     *  String expression of this object
     *
     * @return string
     */
    public function toString()
    {
        return self::getVersion();
    }

    /**
     *    get version info about framework
     *
     *    @param Charcoal_Integer|int $version_part    integer value which classifies version's part
     *
     * @return string
     */
    public static function getVersion( $version_part = NULL )
    {
        $version_part = $version_part ? ui($version_part) : self::VERSION_PART_ALL;

        // パートが個別指定された場合は整数で返す
        $version = NULL;
        switch( $version_part ){
            case self::VERSION_PART_MAJOR:        $version = self::VERSION_MAJOR;        break;
            case self::VERSION_PART_MINOR:        $version = self::VERSION_MINOR;        break;
            case self::VERSION_PART_REVISION:    $version = self::VERSION_REVISION;        break;
            case self::VERSION_PART_BUILD:        $version = self::VERSION_BUILD;        break;
        }
        if ( $version !== NULL ){
            return $version;
        }

        // パートが複数指定された場合は文字列で返す
        $version_string = '';

        if ( $version_part & self::VERSION_PART_MAJOR ){
            $version_string .= self::VERSION_MAJOR;

            if ( $version_part & self::VERSION_PART_MINOR ){
                $version_string .= self::VERSION_STRING_SEPERATOR . self::VERSION_MINOR;

                if ( $version_part & self::VERSION_PART_REVISION ){
                    $version_string .= self::VERSION_STRING_SEPERATOR . self::VERSION_REVISION;

                    if ( $version_part & self::VERSION_PART_BUILD ){
                        $version_string .= self::VERSION_STRING_SEPERATOR . self::VERSION_BUILD;
                    }
                }
            }
        }

        return $version_string;
    }

}


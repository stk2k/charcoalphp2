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
    const VERSION_MAJOR     = 3;
    const VERSION_MINOR     = 2;
    const VERSION_REVISION  = 0;
    const VERSION_BUILD     = 278;

    const VERSION_PART_ALL       = 0xFFFF;
    const VERSION_PART_MAJOR     = 0x0001;
    const VERSION_PART_MINOR     = 0x0002;
    const VERSION_PART_REVISION  = 0x0004;
    const VERSION_PART_BUILD     = 0x0008;
    
    const VERSION_PART_SHORT     = 0x0003;      // VERSION_PART_MAJOR|VERSION_PART_MINOR
    const VERSION_PART_LONG      = 0x0007;      // VERSION_PART_MAJOR|VERSION_PART_MINOR|VERSION_PART_REVISION

    const VERSION_STRING_SEPERATOR = '.';

    private $part;

    /**
     *    constructor
     *
     * @param int $part
     */
    public function __construct( $part = self::VERSION_PART_ALL )
    {
        $this->part = $part;
    }

    /**
     *    get major version
     *
     *    @return int                     major version
     */
    public function getMajorVersion()
    {
        return self::getVersion( i(self::VERSION_PART_MAJOR) );
    }

    /**
     *    get minor version
     *
     *    @return int                     minor version
     */
    public function getMinorVersion()
    {
        return self::getVersion( i(self::VERSION_PART_MINOR) );
    }

    /**
     *    get revision number
     *
     *    @return int                     revision number
     */
    public function getRevision()
    {
        return self::getVersion( i(self::VERSION_PART_REVISION) );
    }

    /**
     *    get build version
     *
     *    @return int                     build version
     */
    public function getBuildNumber()
    {
        return self::getVersion( i(self::VERSION_PART_BUILD) );
    }

    /*
     *  String expression of this object
     *
     * @return string
     */
    public function toString()
    {
        return self::getVersion($this->part);
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


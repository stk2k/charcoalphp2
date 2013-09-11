<?php
/**
* Wildcard file filter
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_WildcardFileFilter implements Charcoal_IFileFilter
{
	private $pattern;
	private $extension;

	/**
	 * Construct object
	 *
	 * string $pattern        regular expression pattern
	 * string $extension      file extension which is ignored in pattern matching.
	 */
	public function __construct( $pattern, $extension = NULL )
	{
//		Charcoal_ParamTrait::checkString( 1, $pattern );
//		Charcoal_ParamTrait::checkString( 2, $extension, TRUE );

		$this->pattern        = $pattern;
		$this->extension      = $extension;
	}

	/**
	 * Check if the filter select the specified file.
	 *
	 * @param Charcoal_File $file         Target fileto be tested.
	 */
	public function accept( Charcoal_File $file )
	{
		if ( $this->extension ){
			$ext = $file->getExtension();
			if ( $ext != $this->extension ){
				return FALSE;
			}
		}

		$suffix = $this->extension ? s('.' . $this->extension) : NULL;
		$name = $suffix ? $file->getName($suffix) : $file->getName();

		if ( fnmatch( $this->pattern, $name ) ){
			return TRUE;
		}
		return FALSE;
	}
}



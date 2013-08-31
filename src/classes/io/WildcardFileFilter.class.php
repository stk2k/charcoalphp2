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
	 * Charcoal_String $pattern        regular expression pattern
	 * Charcoal_String $extension      file extension which is ignored in pattern matching.
	 */
	public function __construct( Charcoal_String $pattern, Charcoal_String $extension = NULL )
	{
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



<?php
/**
* Regular expression file filter
*
* PHP version 5
*
* @package    class.io
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_RegExFileFilter extends Charcoal_AbstractFileFilter
{
	private $pattern;
	private $extension;

	/**
	 * Construct object
	 *
	 * string $pattern        regular expression pattern
	 * string $extension      file extension which is ignored in pattern matching.
	 */
	public function __construct($pattern, $extension = NULL )
	{
//		Charcoal_ParamTrait::validateString( 1, $pattern );
//		Charcoal_ParamTrait::validateString( 2, $extension, TRUE );

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

		if ( preg_match( $this->pattern, $name ) ){
			return TRUE;
		}

		return FALSE;
	}
}



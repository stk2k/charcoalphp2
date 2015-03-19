<?php
/**
* Combined file filter
*
* PHP version 5
*
* @package    class.io
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_CombinedFileFilter extends Charcoal_AbstractFileFilter
{
	private $filters;

	/**
	 * Construct object
	 *
	 * Charcoal_Vector $filters      Array of file filters. All of the elements must implement Charcoal_IFileFilter interface.
	 */
	public function __construct( $filters )
	{
//		Charcoal_ParamTrait::validateVector( 1, $filters );

		$this->filters = $filters;
	}

	/**
	 * Check if the filter select the specified file.
	 *
	 * @param Charcoal_File $file         Target fileto be tested.
	 */
	public function accept( Charcoal_File $file )
	{
		foreach( $this->filters as $filter ){
			if ( $filter->accept($file) ){
				return TRUE;
			}
		}

		return FALSE;
	}
}



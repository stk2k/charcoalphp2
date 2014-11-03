<?php
/**
* Text file rendering target
*
* PHP version 5
*
* @package    objects.events
* @author     CharcoalPHP Development Team
* @copyright  2014 stk2k, sazysoft
*/

class Charcoal_TextFileRenderTarget extends Charcoal_AbstractRenderTarget implements Charcoal_IRenderTarget
{
	private $file_path;
	private $flags;
	private $dir_mode;

	/**
	 *	constructor
	 */
	public function __construct( $file_path, $flags = 0, $dir_mode = '0777' )
	{
		Charcoal_ParamTrait::checkString( 1, $file_path );
		Charcoal_ParamTrait::checkInteger( 2, $flags );
		Charcoal_ParamTrait::checkString( 3, $dir_mode );

		parent::__construct();

		$this->file_path = us($file_path);
		$this->flags = ui($flags) & (FILE_USE_INCLUDE_PATH|FILE_APPEND|LOCK_EX);
		$this->dir_mode = us($dir_mode);
	}

	/**
	 *	render buffer
	 *
	 * @param Charcoal_String|string $buffer    rendering data
	 */
	public function render( $buffer )
	{
		Charcoal_ParamTrait::checkString( 1, $buffer );

		$buffer = us( $buffer );

		if ( is_string($buffer) ){

			$dir = new Charcoal_File( dirname($this->file_path) );

			if ( !$dir->exists() ){
				$dir->makeDirectory( $this->dir_mode );
			}
			if ( !$dir->isWritable() ){
				_throw( new Charcoal_RenderTargetException("directory not writable: $dir") );
			}

			$result = @file_put_contents($this->file_path, $buffer, $this->flags);

			if ( $result === FALSE ){
				$last_error = print_r(error_get_last(),true);
				_throw( new Charcoal_RenderTargetException("file_put_contents failed: {$this->file_path} last error: $last_error") );
			}

		}
	}
}


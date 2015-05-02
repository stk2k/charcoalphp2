<?php
/**
* コンソール出力用例外ハンドラ
*
* PHP version 5
*
* @package    objects.exception_handlers
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class ShellCommandExceptionHandler extends Charcoal_AbstractExceptionHandler
{
	/**
	 * execute exception handlers
	 * 
	 * @param Exception $e     exception to handle
	 * 
	 * @return boolean        TRUE means the exception is handled, otherwise FALSE
	 */
	public function handleException( $e )
	{
		Charcoal_ParamTrait::validateException( 1, $e );

		if ( $e instanceof Charcoal_ProcessEventAtTaskManagerException ){
			$e = $e->getPrevious();

			if ( $e instanceof Charcoal_CreateObjectException ){
				$path = $e->getObjectPath();
				echo "illegal object path: $path" . PHP_EOL;
				return true;
			}
			else if ( $e instanceof Charcoal_ObjectPathFormatException ){
				$path = $e->getObjectPath();
				echo "bad object path: $path" . PHP_EOL;
				return true;
			}
			else if ( $e instanceof Charcoal_ModuleLoaderException ){
				$path = $e->getModulePath();
				echo "failed to load module: $path" . PHP_EOL;
				return true;
			}
			else if ( $e instanceof Charcoal_InvalidShellArgumentException ){
				$option_name = $e->getOptionName();
				$argument = $e->getArgument();
				echo "invalid option[$option_name]: $argument" . PHP_EOL;
				return true;
			}

		}

		return false;
	}

}


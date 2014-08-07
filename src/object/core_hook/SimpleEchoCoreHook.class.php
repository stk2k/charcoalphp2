<?php
/**
* a core hook implementation of simply output into log file
*
* PHP version 5
*
* @package    objects.core_hooks
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_SimpleEchoCoreHook extends Charcoal_AbstractCoreHook
{
	/**
	 * Process core hook message
	 */
	public function processMessage( $stage, $data )
	{
		$stage_name = parent::getCoreHookStageName( $stage );

		switch( $stage ){
		case Charcoal_EnumCoreHookStage::START_OF_BOOTSTRAP:
			Charcoal_Benchmark::start();
			// starting message
			echo "[core stage:$stage_name] Starting framework bootstrap process.". eol();
			echo "[core stage:$stage_name] ===============================================". eol();
			$ver = Charcoal_Framework::getVersion();
			echo "[core stage:$stage_name] CharcoalPHP Framwrork version: $ver". eol();
			echo "[core stage:$stage_name] PHP version: " . PHP_VERSION. eol();
			echo "[core stage:$stage_name] default_timezone: " . date_default_timezone_get(). eol();
			echo "[core stage:$stage_name] ===============================================". eol();
			echo "[core stage:$stage_name] profile=[" . CHARCOAL_PROFILE . "]". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_INIT_FRAMEWORK:
			echo "[core stage:$stage_name] Starting framework initialization process.". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_INIT_FRAMEWORK:
			echo "[core stage:$stage_name] Finished framework initialization process.". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_REG_CLASS_LOADERS:
			echo "[core stage:$stage_name] Starting registering class loaders.". eol();
			break;
		case Charcoal_EnumCoreHookStage::CREATE_FRAMEWORK_CLASS_LOADER:
			echo "[core stage:$stage_name] Created framework class loader.". eol();
			break;
		case Charcoal_EnumCoreHookStage::REG_FRAMEWORK_CLASS_LOADER:
			echo "[core stage:$stage_name] Registered framework class loader.". eol();
			break;
		case Charcoal_EnumCoreHookStage::CREATE_CLASS_LOADER:
			echo "[core stage:$stage_name] Created class loader: [$data]". eol();
			break;
		case Charcoal_EnumCoreHookStage::REG_CLASS_LOADER:
			echo "[core stage:$stage_name] Registered class loader: [$data]". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_CLASS_LOADERS:
			echo "[core stage:$stage_name] Finished registering class loaders.". eol();
			break;
/*
		case Charcoal_EnumCoreHookStage::BEFORE_REG_EXCEPTION_HANDLERS:
			echo "[core stage:$stage_name] Starting registering exception handlers.". eol();
			break;
		case Charcoal_EnumCoreHookStage::CREATE_EXCEPTION_HANDLER:
			echo "[core stage:$stage_name] Registered exception handler: [$data]". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_EXCEPTION_HANDLERS:
			echo "[core stage:$stage_name] Finished registering exception handlers.". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_REG_USER_LOGGERS:
			echo "[core stage:$stage_name] Starting registering loggers.". eol();
			break;
		case Charcoal_EnumCoreHookStage::CREATE_USER_LOGGER:
			echo "[core stage:$stage_name] Registered logger: [" . $data . "]");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_USER_LOGGERS:
			echo "[core stage:$stage_name] Finished registering loggers.". eol();
			break;
*/
		case Charcoal_EnumCoreHookStage::BEFORE_REG_EXTLIB_DIR:
			echo "[core stage:$stage_name] Starting registering external library paths.". eol();
			break;
		case Charcoal_EnumCoreHookStage::ADD_EXTLIB_DIR:
			echo "[core stage:$stage_name] Registered external library path: [$data]". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_EXTLIB_DIR:
			echo "[core stage:$stage_name] Finished registering external library paths.". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_SET_SESSION_HANDLER:
			echo "[core stage:$stage_name] Starting registering session handlers.". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_SET_SESSION_HANDLER:
			echo "[core stage:$stage_name] Finished registering session handlers.". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_START_SESSION:
			echo "[core stage:$stage_name] Starting session.". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_START_SESSION:
			echo "[core stage:$stage_name] Session started.". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_ROUTING_RULE:
			echo "[core stage:$stage_name] Starting creating routing rules.". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_ROUTING_RULE:
			echo "[core stage:$stage_name] Finished creating routing rules.". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_ROUTER:
			echo "[core stage:$stage_name] Starting routing.". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_ROUTER:
			echo "[core stage:$stage_name] Finished routing.". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_CREATE_PROCEDURE:
			echo "[core stage:$stage_name] Creating procedure: [$data]". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_CREATE_PROCEDURE:
			echo "[core stage:$stage_name] Created procedure: [$data]". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_PROCEDURE_FORWARD:
			echo "[core stage:$stage_name] Starting procedure forwarding process.". eol();
			break;
		case Charcoal_EnumCoreHookStage::PRE_PROCEDURE_FORWARD:
			echo "[core stage:$stage_name] Executing procedure forwarding.". eol();
			break;
		case Charcoal_EnumCoreHookStage::POST_PROCEDURE_FORWARD:
			echo "[core stage:$stage_name] Executed procedure forwarding.". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_PROCEDURE_FORWARD:
			echo "[core stage:$stage_name] Finished procedure forwarding process.". eol();
			break;
		case Charcoal_EnumCoreHookStage::END_OF_BOOTSTRAP:
			$elapse = Charcoal_Benchmark::score();
			echo "[core stage:$stage_name] Finished framework bootstrap process.". eol();
			echo "[core stage:$stage_name] bootstrap processing time: [$elapse] msec". eol();
			break;
		case Charcoal_EnumCoreHookStage::PRE_EXECUTE_PROCEDURE:
			$proc_stack = Charcoal_Framework::getProcedureStack();
			echo "[core stage:$stage_name] Executing procedure: [$data]". eol();
			echo "[core stage:$stage_name] procedure stack: [ " . Charcoal_System::implodeArray( ",", $proc_stack ) . " ]". eol();
			break;
		case Charcoal_EnumCoreHookStage::POST_EXECUTE_PROCEDURE:
			echo "[core stage:$stage_name] Executed procedure: [$data]". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_EXECUTE_PROCEDURES:
			echo "[core stage:$stage_name] Finished procedure executing process.". eol();
			break;
		case Charcoal_EnumCoreHookStage::START_OF_SHUTDOWN:
			echo "[core stage:$stage_name] Started framework shutdown process.". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_SAVE_SESSION:
			echo "[core stage:$stage_name] Starting saving session data.". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_SAVE_SESSION:
			echo "[core stage:$stage_name] Finished saving session data.". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_DESTROY_CONTAINER:
			echo "[core stage:$stage_name] Starting destroying container.". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_DESTROY_CONTAINER:
			echo "[core stage:$stage_name] Finished destroying container.". eol();
			break;
/*
		case Charcoal_EnumCoreHookStage::BEFORE_TERMINATE_LOGGERS:
			echo "[core stage:$stage_name] Starting terminating loggers.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_TERMINATE_LOGGERS:
			echo "[core stage:$stage_name] Finished terminating loggers.");
			break;
*/
		case Charcoal_EnumCoreHookStage::END_OF_SHUTDOWN:
			echo "[core stage:$stage_name] Finished framework shutdown process.". eol();

			if ( $this->getSandbox()->isDebug() ){
				// whole ellapse time
				$elapse = Charcoal_Benchmark::stop();
				echo "[core stage:$stage_name] total processing time: [$elapse] msec". eol();

				$peak_usage = memory_get_peak_usage(FALSE);
				$real_usage = memory_get_peak_usage(TRUE);

				$unit_peak = Charcoal_System::formatByteSize( $peak_usage, 5);
				$unit_real = Charcoal_System::formatByteSize( $real_usage, 5);

				echo "[core stage:$stage_name] memory peak usage: [$unit_peak] bytes". eol();
				echo "[core stage:$stage_name] memory real usage: [$unit_real] bytes". eol();

			}
			break;
		}
	}
}


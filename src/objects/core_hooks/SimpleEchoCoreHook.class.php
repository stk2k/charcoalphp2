<?php
/**
* a core hook implementation of simply output into log file
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_SimpleEchoCoreHook extends Charcoal_AbstractCoreHook
{
	/**
	 * Process core hook message
	 */
	public function processMessage( $stage, $data )
	{
		switch( $stage ){
		case Charcoal_EnumCoreHookStage::START_OF_BOOTSTRAP:
			Charcoal_Benchmark::start();
			// starting message
			echo "[$stage] Starting framework bootstrap process.". eol();
			echo "[$stage] ===============================================". eol();
			$ver = Charcoal_Framework::getVersion();
			echo "[$stage] CharcoalPHP Framwrork version: $ver". eol();
			echo "[$stage] PHP version: " . PHP_VERSION. eol();
			echo "[$stage] default_timezone: " . date_default_timezone_get(). eol();
			echo "[$stage] ===============================================". eol();
			echo "[$stage] profile=[" . CHARCOAL_PROFILE . "]". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_INIT_FRAMEWORK:
			echo "[$stage] Starting framework initialization process.". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_INIT_FRAMEWORK:
			echo "[$stage] Finished framework initialization process.". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_REG_CLASS_LOADERS:
			echo "[$stage] Starting registering class loaders.". eol();
			break;
		case Charcoal_EnumCoreHookStage::CREATE_FRAMEWORK_CLASS_LOADER:
			echo "[$stage] Created framework class loader.". eol();
			break;
		case Charcoal_EnumCoreHookStage::REG_FRAMEWORK_CLASS_LOADER:
			echo "[$stage] Registered framework class loader.". eol();
			break;
		case Charcoal_EnumCoreHookStage::CREATE_CLASS_LOADER:
			echo "[$stage] Created class loader: [$data]". eol();
			break;
		case Charcoal_EnumCoreHookStage::REG_CLASS_LOADER:
			echo "[$stage] Registered class loader: [$data]". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_CLASS_LOADERS:
			echo "[$stage] Finished registering class loaders.". eol();
			break;
/*
		case Charcoal_EnumCoreHookStage::BEFORE_REG_EXCEPTION_HANDLERS:
			echo "[$stage] Starting registering exception handlers.". eol();
			break;
		case Charcoal_EnumCoreHookStage::CREATE_EXCEPTION_HANDLER:
			echo "[$stage] Registered exception handler: [$data]". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_EXCEPTION_HANDLERS:
			echo "[$stage] Finished registering exception handlers.". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_REG_USER_LOGGERS:
			echo "[$stage] Starting registering loggers.". eol();
			break;
		case Charcoal_EnumCoreHookStage::CREATE_USER_LOGGER:
			echo "[$stage] Registered logger: [" . $data . "]");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_USER_LOGGERS:
			echo "[$stage] Finished registering loggers.". eol();
			break;
*/
		case Charcoal_EnumCoreHookStage::BEFORE_REG_EXTLIB_DIR:
			echo "[$stage] Starting registering external library paths.". eol();
			break;
		case Charcoal_EnumCoreHookStage::ADD_EXTLIB_DIR:
			echo "[$stage] Registered external library path: [$data]". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_EXTLIB_DIR:
			echo "[$stage] Finished registering external library paths.". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_SET_SESSION_HANDLER:
			echo "[$stage] Starting registering session handlers.". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_SET_SESSION_HANDLER:
			echo "[$stage] Finished registering session handlers.". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_START_SESSION:
			echo "[$stage] Starting session.". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_START_SESSION:
			echo "[$stage] Session started.". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_ROUTING_RULE:
			echo "[$stage] Starting creating routing rules.". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_ROUTING_RULE:
			echo "[$stage] Finished creating routing rules.". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_ROUTER:
			echo "[$stage] Starting routing.". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_ROUTER:
			echo "[$stage] Finished routing.". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_CREATE_PROCEDURE:
			echo "[$stage] Creating procedure: [$data]". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_CREATE_PROCEDURE:
			echo "[$stage] Created procedure: [$data]". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_PROCEDURE_FORWARD:
			echo "[$stage] Starting procedure forwarding process.". eol();
			break;
		case Charcoal_EnumCoreHookStage::PRE_PROCEDURE_FORWARD:
			echo "[$stage] Executing procedure forwarding.". eol();
			break;
		case Charcoal_EnumCoreHookStage::POST_PROCEDURE_FORWARD:
			echo "[$stage] Executed procedure forwarding.". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_PROCEDURE_FORWARD:
			echo "[$stage] Finished procedure forwarding process.". eol();
			break;
/*		case Charcoal_EnumCoreHookStage::BEFORE_CREATE_CONTAINER:
			echo "[$stage] Starting creating container.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_CREATE_CONTAINER:
			echo "[$stage] Finished creating container.");
			break;
*/
/*
		case Charcoal_EnumCoreHookStage::BEFORE_REG_RESPONSE_FILTERS:
			echo "[$stage] Starting creating response filters.");
			break;
		case Charcoal_EnumCoreHookStage::CREATE_RESPONSE_FILTER:
			echo "[$stage] Created response filter: [$data]");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_RESPONSE_FILTERS:
			echo "[$stage] Finished creating response filters.");
			break;
*/
		case Charcoal_EnumCoreHookStage::END_OF_BOOTSTRAP:
			$elapse = Charcoal_Benchmark::score();
			echo "[$stage] Finished framework bootstrap process.". eol();
			echo "[$stage] bootstrap processing time: [$elapse] msec". eol();
			break;
		case Charcoal_EnumCoreHookStage::PRE_EXECUTE_PROCEDURE:
			$proc_stack = Charcoal_Framework::getProcedureStack();
			echo "[$stage] Executing procedure: [$data]". eol();
			echo "[$stage] procedure stack: [ " . Charcoal_System::implodeArray( ",", $proc_stack ) . " ]". eol();
			break;
		case Charcoal_EnumCoreHookStage::POST_EXECUTE_PROCEDURE:
			echo "[$stage] Executed procedure: [$data]". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_EXECUTE_PROCEDURES:
			echo "[$stage] Finished procedure executing process.". eol();
			break;
		case Charcoal_EnumCoreHookStage::START_OF_SHUTDOWN:
			echo "[$stage] Started framework shutdown process.". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_SAVE_SESSION:
			echo "[$stage] Starting saving session data.". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_SAVE_SESSION:
			echo "[$stage] Finished saving session data.". eol();
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_DESTROY_CONTAINER:
			echo "[$stage] Starting destroying container.". eol();
			break;
		case Charcoal_EnumCoreHookStage::AFTER_DESTROY_CONTAINER:
			echo "[$stage] Finished destroying container.". eol();
			break;
/*
		case Charcoal_EnumCoreHookStage::BEFORE_TERMINATE_LOGGERS:
			echo "[$stage] Starting terminating loggers.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_TERMINATE_LOGGERS:
			echo "[$stage] Finished terminating loggers.");
			break;
*/
		case Charcoal_EnumCoreHookStage::END_OF_SHUTDOWN:
			echo "[$stage] Finished framework shutdown process.". eol();

			if ( $this->getSandbox()->isDebug() ){
				// whole ellapse time
				$elapse = Charcoal_Benchmark::stop();
				echo "[$stage] total processing time: [$elapse] msec". eol();

				$peak_usage = memory_get_peak_usage(FALSE);
				$real_usage = memory_get_peak_usage(TRUE);

				$unit_peak = Charcoal_System::formatByteSize( $peak_usage, 5);
				$unit_real = Charcoal_System::formatByteSize( $real_usage, 5);

				echo "[$stage] memory peak usage: [$unit_peak] bytes". eol();
				echo "[$stage] memory real usage: [$unit_real] bytes". eol();

			}
			break;
		}
	}
}


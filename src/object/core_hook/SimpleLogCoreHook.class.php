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

class Charcoal_SimpleLogCoreHook extends Charcoal_AbstractCoreHook
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
			log_info( 'system,debug,screen', "[core stage:$stage_name] Starting framework bootstrap process." );
			log_info( 'system,debug,screen', "[core stage:$stage_name] ===============================================" );
			$ver = Charcoal_Framework::getVersion();
			log_info( 'system,debug,screen', "[core stage:$stage_name] CharcoalPHP Framwrork version: $ver" );
			log_info( 'system,debug,screen', "[core stage:$stage_name] PHP version: " . PHP_VERSION );
			log_info( 'system,debug,screen', "[core stage:$stage_name] default_timezone: " . date_default_timezone_get() );
			log_info( 'system,debug,screen', "[core stage:$stage_name] ===============================================" );
			log_info( 'system,debug,screen', "[core stage:$stage_name] profile=[" . CHARCOAL_PROFILE . "]" );
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_INIT_FRAMEWORK:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Starting framework initialization process." );
			break;
		case Charcoal_EnumCoreHookStage::AFTER_INIT_FRAMEWORK:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Finished framework initialization process." );
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_REG_CLASS_LOADERS:
			log_info( "system,debug,class_loader", "[core stage:$stage_name] Starting registering class loaders." );
			break;
		case Charcoal_EnumCoreHookStage::CREATE_FRAMEWORK_CLASS_LOADER:
			log_info( "system,debug,class_loader", "[core stage:$stage_name] Created framework class loader." );
			break;
		case Charcoal_EnumCoreHookStage::REG_FRAMEWORK_CLASS_LOADER:
			log_info( "system,debug,class_loader", "[core stage:$stage_name] Registered framework class loader." );
			break;
		case Charcoal_EnumCoreHookStage::CREATE_CLASS_LOADER:
			log_info( "system,debug,class_loader", "[core stage:$stage_name] Created class loader: [$data]" );
			break;
		case Charcoal_EnumCoreHookStage::REG_CLASS_LOADER:
			log_info( "system,debug,class_loader", "[core stage:$stage_name] Registered class loader: [$data]" );
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_CLASS_LOADERS:
			log_info( "system,debug,class_loader", "[core stage:$stage_name] Finished registering class loaders." );
			break;
/*
		case Charcoal_EnumCoreHookStage::BEFORE_REG_EXCEPTION_HANDLERS:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Starting registering exception handlers." );
			break;
		case Charcoal_EnumCoreHookStage::CREATE_EXCEPTION_HANDLER:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Registered exception handler: [$data]" );
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_EXCEPTION_HANDLERS:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Finished registering exception handlers." );
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_REG_USER_LOGGERS:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Starting registering loggers." );
			break;
		case Charcoal_EnumCoreHookStage::CREATE_USER_LOGGER:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Registered logger: [" . $data . "]");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_USER_LOGGERS:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Finished registering loggers." );
			break;
*/
		case Charcoal_EnumCoreHookStage::BEFORE_REG_EXTLIB_DIR:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Starting registering external library paths." );
			break;
		case Charcoal_EnumCoreHookStage::ADD_EXTLIB_DIR:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Registered external library path: [$data]" );
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_EXTLIB_DIR:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Finished registering external library paths." );
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_SET_SESSION_HANDLER:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Starting registering session handlers.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_SET_SESSION_HANDLER:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Finished registering session handlers.");
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_START_SESSION:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Starting session." );
			break;
		case Charcoal_EnumCoreHookStage::AFTER_START_SESSION:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Session started." );
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_ROUTING_RULE:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Starting creating routing rules.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_ROUTING_RULE:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Finished creating routing rules.");
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_ROUTER:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Starting routing.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_ROUTER:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Finished routing.");
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_CREATE_PROCEDURE:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Creating procedure: [$data]");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_CREATE_PROCEDURE:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Created procedure: [$data]");
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_PROCEDURE_FORWARD:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Starting procedure forwarding process.");
			break;
		case Charcoal_EnumCoreHookStage::PRE_PROCEDURE_FORWARD:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Executing procedure forwarding.");
			break;
		case Charcoal_EnumCoreHookStage::POST_PROCEDURE_FORWARD:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Executed procedure forwarding.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_PROCEDURE_FORWARD:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Finished procedure forwarding process.");
			break;
/*		case Charcoal_EnumCoreHookStage::BEFORE_CREATE_CONTAINER:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Starting creating container.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_CREATE_CONTAINER:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Finished creating container.");
			break;
*/
/*
		case Charcoal_EnumCoreHookStage::BEFORE_REG_RESPONSE_FILTERS:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Starting creating response filters.");
			break;
		case Charcoal_EnumCoreHookStage::CREATE_RESPONSE_FILTER:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Created response filter: [$data]");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_RESPONSE_FILTERS:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Finished creating response filters.");
			break;
*/
		case Charcoal_EnumCoreHookStage::END_OF_BOOTSTRAP:
			$elapse = Charcoal_Benchmark::score();
			log_info( 'system,debug,screen', "[core stage:$stage_name] Finished framework bootstrap process.");
			log_info( 'system,debug,screen', "[core stage:$stage_name] bootstrap processing time: [$elapse] msec");
			break;
		case Charcoal_EnumCoreHookStage::PRE_EXECUTE_PROCEDURE:
			$proc_stack = Charcoal_Framework::getProcedureStack();
			log_info( 'system,debug,screen', "[core stage:$stage_name] Executing procedure: [$data]");
			log_info( 'system,debug,screen', "[core stage:$stage_name] procedure stack: [ " . Charcoal_System::implodeArray( ",", $proc_stack ) . " ]" );
			break;
		case Charcoal_EnumCoreHookStage::POST_EXECUTE_PROCEDURE:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Executed procedure: [$data]");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_EXECUTE_PROCEDURES:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Finished procedure executing process.");
			break;
		case Charcoal_EnumCoreHookStage::START_OF_SHUTDOWN:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Started framework shutdown process.");
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_SAVE_SESSION:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Starting saving session data.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_SAVE_SESSION:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Finished saving session data.");
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_DESTROY_CONTAINER:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Starting destroying container.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_DESTROY_CONTAINER:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Finished destroying container.");
			break;
/*
		case Charcoal_EnumCoreHookStage::BEFORE_TERMINATE_LOGGERS:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Starting terminating loggers.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_TERMINATE_LOGGERS:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Finished terminating loggers.");
			break;
*/
		case Charcoal_EnumCoreHookStage::END_OF_SHUTDOWN:
			log_info( 'system,debug,screen', "[core stage:$stage_name] Finished framework shutdown process.");

			if ( $this->getSandbox()->isDebug() ){
				// whole ellapse time
				$elapse = Charcoal_Benchmark::stop();
				log_info( 'system,debug,screen', "[core stage:$stage_name] total processing time: [$elapse] msec");

				$peak_usage = memory_get_peak_usage(FALSE);
				$real_usage = memory_get_peak_usage(TRUE);

				$unit_peak = Charcoal_System::formatByteSize( $peak_usage, 5 );
				$unit_real = Charcoal_System::formatByteSize( $real_usage, 5 );

				log_info( 'system,debug,screen', "[core stage:$stage_name] memory peak usage: [$unit_peak] bytes");
				log_info( 'system,debug,screen', "[core stage:$stage_name] memory real usage: [$unit_real] bytes");

			}
			break;
		}
	}
}


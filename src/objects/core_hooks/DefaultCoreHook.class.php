<?php
/**
* Default core hook implementation for Charcoal_ICoreHook interface.
*
* PHP version 5
*
* @package    base
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_DefaultCoreHook extends Charcoal_AbstractCoreHook
{
	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

	}

	/**
	 * Process core hook message
	 */
	public function processMessage( $stage, $data )
	{
		switch( $stage ){
		case Charcoal_EnumCoreHookStage::START_OF_BOOTSTRAP:
			Charcoal_Benchmark::start();
			// starting message
			log_info( 'system,debug,screen', "[$stage] Starting framework bootstrap process." );
			log_info( 'system,debug,screen', "[$stage] ===============================================" );
			$ver = Charcoal_Framework::getVersion();
			log_info( 'system,debug,screen', "[$stage] CharcoalPHP Framwrork version: $ver" );
			log_info( 'system,debug,screen', "[$stage] PHP version: " . PHP_VERSION );
			log_info( 'system,debug,screen', "[$stage] default_timezone: " . date_default_timezone_get() );
			log_info( 'system,debug,screen', "[$stage] ===============================================" );
			log_info( 'system,debug,screen', "[$stage] profile=[" . CHARCOAL_PROFILE . "]" );
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_INIT_FRAMEWORK:
			log_info( 'system,debug,screen', "[$stage] Starting framework initialization process." );
			break;
		case Charcoal_EnumCoreHookStage::AFTER_INIT_FRAMEWORK:
			log_info( 'system,debug,screen', "[$stage] Finished framework initialization process." );
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_REG_CLASS_LOADERS:
			log_info( "system,debug,class_loader", "[$stage] Starting registering class loaders." );
			break;
		case Charcoal_EnumCoreHookStage::CREATE_FRAMEWORK_CLASS_LOADER:
			log_info( "system,debug,class_loader", "[$stage] Created framework class loader." );
			break;
		case Charcoal_EnumCoreHookStage::REG_FRAMEWORK_CLASS_LOADER:
			log_info( "system,debug,class_loader", "[$stage] Registered framework class loader." );
			break;
		case Charcoal_EnumCoreHookStage::CREATE_CLASS_LOADER:
			log_info( "system,debug,class_loader", "[$stage] Created class loader: [$data]" );
			break;
		case Charcoal_EnumCoreHookStage::REG_CLASS_LOADER:
			log_info( "system,debug,class_loader", "[$stage] Registered class loader: [$data]" );
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_CLASS_LOADERS:
			log_info( "system,debug,class_loader", "[$stage] Finished registering class loaders." );
			break;
/*
		case Charcoal_EnumCoreHookStage::BEFORE_REG_EXCEPTION_HANDLERS:
			log_info( 'system,debug,screen', "[$stage] Starting registering exception handlers." );
			break;
		case Charcoal_EnumCoreHookStage::CREATE_EXCEPTION_HANDLER:
			log_info( 'system,debug,screen', "[$stage] Registered exception handler: [$data]" );
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_EXCEPTION_HANDLERS:
			log_info( 'system,debug,screen', "[$stage] Finished registering exception handlers." );
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_REG_USER_LOGGERS:
			log_info( 'system,debug,screen', "[$stage] Starting registering loggers." );
			break;
		case Charcoal_EnumCoreHookStage::CREATE_USER_LOGGER:
			log_info( 'system,debug,screen', "[$stage] Registered logger: [" . $data . "]");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_USER_LOGGERS:
			log_info( 'system,debug,screen', "[$stage] Finished registering loggers." );
			break;
*/
		case Charcoal_EnumCoreHookStage::BEFORE_REG_EXTLIB_DIR:
			log_info( 'system,debug,screen', "[$stage] Starting registering external library paths." );
			break;
		case Charcoal_EnumCoreHookStage::ADD_EXTLIB_DIR:
			log_info( 'system,debug,screen', "[$stage] Registered external library path: [$data]" );
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_EXTLIB_DIR:
			log_info( 'system,debug,screen', "[$stage] Finished registering external library paths." );
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_SET_SESSION_HANDLER:
			log_info( 'system,debug,screen', "[$stage] Starting registering session handlers.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_SET_SESSION_HANDLER:
			log_info( 'system,debug,screen', "[$stage] Finished registering session handlers.");
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_START_SESSION:
			log_info( 'system,debug,screen', "[$stage] Starting session." );
			break;
		case Charcoal_EnumCoreHookStage::AFTER_START_SESSION:
			log_info( 'system,debug,screen', "[$stage] Session started." );
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_ROUTING_RULE:
			log_info( 'system,debug,screen', "[$stage] Starting creating routing rules.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_ROUTING_RULE:
			log_info( 'system,debug,screen', "[$stage] Finished creating routing rules.");
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_ROUTER:
			log_info( 'system,debug,screen', "[$stage] Starting routing.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_ROUTER:
			log_info( 'system,debug,screen', "[$stage] Finished routing.");
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_CREATE_PROCEDURE:
			log_info( 'system,debug,screen', "[$stage] Creating procedure: [$data]");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_CREATE_PROCEDURE:
			log_info( 'system,debug,screen', "[$stage] Created procedure: [$data]");
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_PROCEDURE_FORWARD:
			log_info( 'system,debug,screen', "[$stage] Starting procedure forwarding process.");
			break;
		case Charcoal_EnumCoreHookStage::PRE_PROCEDURE_FORWARD:
			log_info( 'system,debug,screen', "[$stage] Executing procedure forwarding.");
			break;
		case Charcoal_EnumCoreHookStage::POST_PROCEDURE_FORWARD:
			log_info( 'system,debug,screen', "[$stage] Executed procedure forwarding.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_PROCEDURE_FORWARD:
			log_info( 'system,debug,screen', "[$stage] Finished procedure forwarding process.");
			break;
/*		case Charcoal_EnumCoreHookStage::BEFORE_CREATE_CONTAINER:
			log_info( 'system,debug,screen', "[$stage] Starting creating container.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_CREATE_CONTAINER:
			log_info( 'system,debug,screen', "[$stage] Finished creating container.");
			break;
*/
/*
		case Charcoal_EnumCoreHookStage::BEFORE_REG_RESPONSE_FILTERS:
			log_info( 'system,debug,screen', "[$stage] Starting creating response filters.");
			break;
		case Charcoal_EnumCoreHookStage::CREATE_RESPONSE_FILTER:
			log_info( 'system,debug,screen', "[$stage] Created response filter: [$data]");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_RESPONSE_FILTERS:
			log_info( 'system,debug,screen', "[$stage] Finished creating response filters.");
			break;
*/
		case Charcoal_EnumCoreHookStage::END_OF_BOOTSTRAP:
			$elapse = Charcoal_Benchmark::score();
			log_info( 'system,debug,screen', "[$stage] Finished framework bootstrap process.");
			log_info( 'system,debug,screen', "[$stage] bootstrap processing time: [$elapse] msec");
			break;
		case Charcoal_EnumCoreHookStage::PRE_EXECUTE_PROCEDURE:
			$proc_stack = Charcoal_Framework::getProcedureStack();
			log_info( 'system,debug,screen', "[$stage] Executing procedure: [$data]");
			log_info( 'system,debug,screen', "[$stage] procedure stack: [ " . Charcoal_System::implodeArray( ",", $proc_stack ) . " ]" );
			break;
		case Charcoal_EnumCoreHookStage::POST_EXECUTE_PROCEDURE:
			log_info( 'system,debug,screen', "[$stage] Executed procedure: [$data]");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_EXECUTE_PROCEDURES:
			log_info( 'system,debug,screen', "[$stage] Finished procedure executing process.");
			break;
		case Charcoal_EnumCoreHookStage::START_OF_SHUTDOWN:
			log_info( 'system,debug,screen', "[$stage] Started framework shutdown process.");
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_SAVE_SESSION:
			log_info( 'system,debug,screen', "[$stage] Starting saving session data.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_SAVE_SESSION:
			log_info( 'system,debug,screen', "[$stage] Finished saving session data.");
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_DESTROY_CONTAINER:
			log_info( 'system,debug,screen', "[$stage] Starting destroying container.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_DESTROY_CONTAINER:
			log_info( 'system,debug,screen', "[$stage] Finished destroying container.");
			break;
/*
		case Charcoal_EnumCoreHookStage::BEFORE_TERMINATE_LOGGERS:
			log_info( 'system,debug,screen', "[$stage] Starting terminating loggers.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_TERMINATE_LOGGERS:
			log_info( 'system,debug,screen', "[$stage] Finished terminating loggers.");
			break;
*/
		case Charcoal_EnumCoreHookStage::END_OF_SHUTDOWN:
			log_info( 'system,debug,screen', "[$stage] Finished framework shutdown process.");

			if ( $this->getSandbox()->isDebug() ){
				// whole ellapse time
				$elapse = Charcoal_Benchmark::stop();
				log_info( 'system,debug,screen', "[$stage] total processing time: [$elapse] msec");

				$peak_usage = memory_get_peak_usage(FALSE);
				$real_usage = memory_get_peak_usage(TRUE);

				$unit_peak = Charcoal_System::formatByteSize( $peak_usage, 5 );
				$unit_real = Charcoal_System::formatByteSize( $real_usage, 5 );

				log_info( 'system,debug,screen', "[$stage] memory peak usage: [$unit_peak] bytes");
				log_info( 'system,debug,screen', "[$stage] memory real usage: [$unit_real] bytes");

			}
			break;
		}
	}
}


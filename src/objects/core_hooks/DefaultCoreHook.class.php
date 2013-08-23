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

class Charcoal_DefaultCoreHook extends Charcoal_CharcoalObject implements Charcoal_ICoreHook
{
	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( Charcoal_Config $config )
	{
	}

	/**
	 * Process core hook message
	 */
	public function process( Charcoal_CoreHookMessage $msg )
	{
		static $start = 0;

		$stage    = $msg->getStage();
		$data     = $msg->getData();

		switch( ui($stage) ){
		case Charcoal_EnumCoreHookStage::START_OF_BOOTSTRAP:
			$start = Charcoal_Benchmark::nowTime();
			// starting message
			log_info( "system,debug", "core_hook", "[$stage] Starting framework bootstrap process." );
			log_info( "system,debug", "core_hook", "[$stage] ===============================================" );
			log_info( "system,debug", "core_hook", "[$stage] CcharcoalPHP Framwrork version: " . Charcoal_Framework::getVersion() );
			log_info( "system,debug", "core_hook", "[$stage] PHP version: " . PHP_VERSION );
			log_info( "system,debug", "core_hook", "[$stage] default_timezone: " . date_default_timezone_get() );
			log_info( "system,debug", "core_hook", "[$stage] ===============================================" );
			$profile_config_file = Charcoal_Profile::getConfigFile();
			
			log_info( "system,debug", "core_hook", "[$stage] profile=[" . CHARCOAL_PROFILE . "]" );
			log_info( "system,debug", "core_hook", "[$stage] profile config=[$profile_config_file]" );
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_INIT_FRAMEWORK:
			log_info( "system,debug", "core_hook", "[$stage] Starting framework initialization process." );
			break;
		case Charcoal_EnumCoreHookStage::AFTER_INIT_FRAMEWORK:
			log_info( "system,debug", "core_hook", "[$stage] Finished framework initialization process." );
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_REG_CLASS_LOADERS:
			log_info( "system,debug,class_loader", "core_hook", "[$stage] Starting registering class loaders." );
			break;
		case Charcoal_EnumCoreHookStage::CREATE_FRAMEWORK_CLASS_LOADER:
			log_info( "system,debug,class_loader", "core_hook", "[$stage] Created framework class loader." );
			break;
		case Charcoal_EnumCoreHookStage::REG_FRAMEWORK_CLASS_LOADER:
			log_info( "system,debug,class_loader", "core_hook", "[$stage] Registered framework class loader." );
			break;
		case Charcoal_EnumCoreHookStage::CREATE_CLASS_LOADER:
			log_info( "system,debug,class_loader", "core_hook", "[$stage] Created class loader: [$data]" );
			break;
		case Charcoal_EnumCoreHookStage::REG_CLASS_LOADER:
			log_info( "system,debug,class_loader", "core_hook", "[$stage] Registered class loader: [$data]" );
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_CLASS_LOADERS:
			log_info( "system,debug,class_loader", "core_hook", "[$stage] Finished registering class loaders." );
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_REG_EXCEPTION_HANDLERS:
			log_info( "system,debug", "core_hook", "[$stage] Starting registering exception handlers." );
			break;
		case Charcoal_EnumCoreHookStage::CREATE_EXCEPTION_HANDLER:
			log_info( "system,debug", "core_hook", "[$stage] Registered exception handler: [$data]" );
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_EXCEPTION_HANDLERS:
			log_info( "system,debug", "core_hook", "[$stage] Finished registering exception handlers." );
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_REG_USER_LOGGERS:
			log_info( "system,debug", "core_hook", "[$stage] Starting registering loggers." );
			break;
		case Charcoal_EnumCoreHookStage::CREATE_USER_LOGGER:
			log_info( "system,debug", "core_hook", "[$stage] Registered logger: [" . $data . "]");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_USER_LOGGERS:
			log_info( "system,debug", "core_hook", "[$stage] Finished registering loggers." );
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_REG_EXTLIB_DIR:
			log_info( "system,debug", "core_hook", "[$stage] Starting registering external library paths." );
			break;
		case Charcoal_EnumCoreHookStage::ADD_EXTLIB_DIR:
			log_info( "system,debug", "core_hook", "[$stage] Registered external library path: [$data]" );
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_EXTLIB_DIR:
			log_info( "system,debug", "core_hook", "[$stage] Finished registering external library paths." );
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_SET_SESSION_HANDLER:
			log_info( "system,debug", "core_hook", "[$stage] Starting registering session handlers.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_SET_SESSION_HANDLER:
			log_info( "system,debug", "core_hook", "[$stage] Finished registering session handlers.");
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_START_SESSION:
			log_info( "system,debug", "core_hook", "[$stage] Starting session." );
			break;
		case Charcoal_EnumCoreHookStage::AFTER_START_SESSION:
			log_info( "system,debug", "core_hook", "[$stage] Session started." );
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_ROUTING_RULE:
			log_info( "system,debug", "core_hook", "[$stage] Starting creating routing rules.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_ROUTING_RULE:
			log_info( "system,debug", "core_hook", "[$stage] Finished creating routing rules.");
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_ROUTER:
			log_info( "system,debug", "core_hook", "[$stage] Starting routing.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_ROUTER:
			log_info( "system,debug", "core_hook", "[$stage] Finished routing.");
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_CREATE_PROCEDURE:
			log_info( "system,debug", "core_hook", "[$stage] Creating procedure: [$data]");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_CREATE_PROCEDURE:
			log_info( "system,debug", "core_hook", "[$stage] Created procedure: [$data]");
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_PROCEDURE_FORWARD:
			log_info( "system,debug", "core_hook", "[$stage] Starting procedure forwarding process.");
			break;
		case Charcoal_EnumCoreHookStage::PRE_PROCEDURE_FORWARD:
			log_info( "system,debug", "core_hook", "[$stage] Executing procedure forwarding.");
			break;
		case Charcoal_EnumCoreHookStage::POST_PROCEDURE_FORWARD:
			log_info( "system,debug", "core_hook", "[$stage] Executed procedure forwarding.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_PROCEDURE_FORWARD:
			log_info( "system,debug", "core_hook", "[$stage] Finished procedure forwarding process.");
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_CREATE_CONTAINER:
			log_info( "system,debug", "core_hook", "[$stage] Starting creating container.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_CREATE_CONTAINER:
			log_info( "system,debug", "core_hook", "[$stage] Finished creating container.");
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_REG_RESPONSE_FILTERS:
			log_info( "system,debug", "core_hook", "[$stage] Starting creating response filters.");
			break;
		case Charcoal_EnumCoreHookStage::CREATE_RESPONSE_FILTER:
			log_info( "system,debug", "core_hook", "[$stage] Created response filter: [$data]");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_REG_RESPONSE_FILTERS:
			log_info( "system,debug", "core_hook", "[$stage] Finished creating response filters.");
			break;
		case Charcoal_EnumCoreHookStage::END_OF_BOOTSTRAP:
			$now = Charcoal_Benchmark::nowTime();
			$elapse = round( ($now - $start) * 1000, 5 );
			log_info( "system,debug", "core_hook", "[$stage] Finished framework bootstrap process.");
			log_info( "system,debug", "core_hook", "[$stage] bootstrap processing time: [$elapse] msec");
			break;
		case Charcoal_EnumCoreHookStage::PRE_EXECUTE_PROCEDURE:
			$proc_stack = Charcoal_Framework::getProcedureStack();
			log_info( "system,debug", "core_hook", "[$stage] Executing procedure: [$data]");
			log_info( "system,debug", "core_hook", "[$stage] procedure stack: [ " . Charcoal_System::implodeArray( ",", $proc_stack ) . " ]" );
			break;
		case Charcoal_EnumCoreHookStage::POST_EXECUTE_PROCEDURE:
			log_info( "system,debug", "core_hook", "[$stage] Executed procedure: [$data]");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_EXECUTE_PROCEDURES:
			log_info( "system,debug", "core_hook", "[$stage] Finished procedure executing process.");
			break;
		case Charcoal_EnumCoreHookStage::START_OF_SHUTDOWN:
			log_info( "system,debug", "core_hook", "[$stage] Started framework shutdown process.");
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_SAVE_SESSION:
			log_info( "system,debug", "core_hook", "[$stage] Starting saving session data.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_SAVE_SESSION:
			log_info( "system,debug", "core_hook", "[$stage] Finished saving session data.");
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_DESTROY_CONTAINER:
			log_info( "system,debug", "core_hook", "[$stage] Starting destroying container.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_DESTROY_CONTAINER:
			log_info( "system,debug", "core_hook", "[$stage] Finished destroying container.");
			break;
		case Charcoal_EnumCoreHookStage::BEFORE_TERMINATE_LOGGERS:
			log_info( "system,debug", "core_hook", "[$stage] Starting terminating loggers.");
			break;
		case Charcoal_EnumCoreHookStage::AFTER_TERMINATE_LOGGERS:
			log_info( "system,debug", "core_hook", "[$stage] Finished terminating loggers.");
			break;
		case Charcoal_EnumCoreHookStage::END_OF_SHUTDOWN:
			log_info( "system,debug", "core_hook", "[$stage] Finished framework shutdown process.");

			if ( Charcoal_Framework::isDebugMode() ){
				// whole ellapse time
				$now = Charcoal_Benchmark::nowTime();
				$elapse = round( $now - $start, 4 );
				log_info( "system,debug", "core_hook", "[$stage] total processing time: [$elapse] msec");

				$peak_usage = memory_get_peak_usage(FALSE);
				$real_usage = memory_get_peak_usage(TRUE);

				$unit_peak = Charcoal_System::formatByteSize( $peak_usage, 5 );
				$unit_real = Charcoal_System::formatByteSize( $real_usage, 5 );

				log_info( "system,debug", "core_hook", "[$stage] memory peak usage: [$unit_peak] bytes");
				log_info( "system,debug", "core_hook", "[$stage] memory real usage: [$unit_real] bytes");
/*
				$node = new Charcoal_XmlElement( s("div") );

				$node->addContents( s("CharcoalPHP ver." . implode(".", $versions) . " debug mode<hr>") );
				$node->addContents( s("time: $elapse msec<br>") );
				$node->addContents( s("peak_usage: $unit_peak<br>") );
				$node->addContents( s("real_usage: $unit_real<br>") );

				$window = new Charcoal_PopupDebugWindow();

				$root_node = $window->getBody()->add( $node );

				$window->popup();
*/
			}
			break;
		}
	}
}
return __FILE__;

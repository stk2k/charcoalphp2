<?php
/**
* フレームワーク用クラスローダ
*
* PHP version 5
*
* @package    core
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_FrameworkClassLoader extends Charcoal_CharcoalObject implements Charcoal_IClassLoader
{
	static $class_paths;

	/**
	 *	constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/*
	 * クラスパスの初期化
	 */
	public static function initClassPath()
	{
		self::$class_paths = array(

		// base classes
				'Charcoal_CharcoalComponent'		=> 'classes/base',
				'Charcoal_DTO'						=> 'classes/base',
				'Charcoal_ImageFile'				=> 'classes/base',
				'Charcoal_Position'					=> 'classes/base',
				'Charcoal_PositionFloat'			=> 'classes/base',
				'Charcoal_Rectangle'				=> 'classes/base',
				'Charcoal_RectangleFloat'			=> 'classes/base',
				'Charcoal_URL'						=> 'classes/base',

		// enum constant classes
				'Charcoal_EnumSmtpStatusCode'		=> 'constants',
				'Charcoal_EnumEventPriority'		=> 'constants',
				'Charcoal_EnumSQLJoinType'			=> 'constants',
				'Charcoal_EnumSQLAggregateFunc'		=> 'constants',

		// debug classes
				'Charcoal_Benchmark'				=> 'classes/debug',
				'Charcoal_CallHistory'				=> 'classes/debug',
				'Charcoal_DebugProfiler'			=> 'classes/debug',
				'Charcoal_MethodSpec'				=> 'classes/debug',
				'Charcoal_FunctionSpec'				=> 'classes/debug',
				'Charcoal_PhpSourceElement'			=> 'classes/debug',
				'Charcoal_PhpSourceInfo'			=> 'classes/debug',
				'Charcoal_PhpSourceParser'			=> 'classes/debug',
				'Charcoal_PhpSourceRenderer'		=> 'classes/debug',
				'Charcoal_PopupDebugWindow'			=> 'classes/debug',

		// core classes
				'Charcoal_AnnotationValue'			=> 'classes/core',
				'Charcoal_Cache'					=> 'classes/core',
				'Charcoal_Cookie'					=> 'classes/core',
				'Charcoal_EventContext'				=> 'classes/core',
				'Charcoal_EventQueue'				=> 'classes/core',
				'Charcoal_HttpHeader'				=> 'classes/core',
				'Charcoal_Layout'					=> 'classes/core',
				'Charcoal_QueryJoin'				=> 'classes/core',
				'Charcoal_ModuleLoader'				=> 'classes/core',
				'Charcoal_ResponseFilterList'		=> 'classes/core',
				'Charcoal_Sequence'					=> 'classes/core',
				'Charcoal_SequenceHolder'			=> 'classes/core',
				'Charcoal_Session'					=> 'classes/core',
				'Charcoal_SimpleModule'				=> 'classes/core',
				'Charcoal_TableModelCache'			=> 'classes/core',
				'Charcoal_TransformerCache'			=> 'classes/core',

		// utility classes
				'Charcoal_CommandLineUtil'			=> 'classes/util',
				'Charcoal_DBPageInfo'				=> 'classes/util',
				'Charcoal_EncodingConverter'		=> 'classes/util',
				'Charcoal_UploadedFile'				=> 'classes/util',
				'Charcoal_FileSystemUtil'			=> 'classes/util',
				'Charcoal_GraphicsUtil'				=> 'classes/util',
				'Charcoal_JsonUtil'					=> 'classes/util',
				'Charcoal_MailUtil'					=> 'classes/util',
				'Charcoal_SQLUtil'					=> 'classes/util',
				'Charcoal_URLUtil'					=> 'classes/util',
				'Charcoal_XmlUtil'					=> 'classes/util',

		// interface classes
				'Charcoal_ICacheDriver'				=> 'interfaces',
				'Charcoal_ICharcoalObject'			=> 'interfaces',
				'Charcoal_IClassLoader'				=> 'interfaces',
				'Charcoal_IComponent'				=> 'interfaces',
				'Charcoal_IConfigValidator'			=> 'interfaces',
				'Charcoal_ICoreHook'				=> 'interfaces',
				'Charcoal_IDataSource'				=> 'interfaces',
				'Charcoal_IDebugtraceRenderer'		=> 'interfaces',
				'Charcoal_IEvent'					=> 'interfaces',
				'Charcoal_IEventContext'			=> 'interfaces',
				'Charcoal_IExceptionHandler'		=> 'interfaces',
				'Charcoal_IFileFilter'				=> 'interfaces',
				'Charcoal_IHashable'				=> 'interfaces',
				'Charcoal_ILayoutManager'			=> 'interfaces',
				'Charcoal_ILogger'					=> 'interfaces',
				'Charcoal_IModel'					=> 'interfaces',
				'Charcoal_IModule'					=> 'interfaces',
				'Charcoal_IProcedure'				=> 'interfaces',
				'Charcoal_IProperties'				=> 'interfaces',
				'Charcoal_IRedirectLayout'			=> 'interfaces',
				'Charcoal_IRequest'					=> 'interfaces',
				'Charcoal_IResponse'				=> 'interfaces',
				'Charcoal_IResponseFilter'			=> 'interfaces',
				'Charcoal_IRouter'					=> 'interfaces',
				'Charcoal_IRoutingRule'				=> 'interfaces',
				'Charcoal_ISessionHandler'			=> 'interfaces',
				'Charcoal_IStateful'				=> 'interfaces',
				'Charcoal_ISequence'				=> 'interfaces',
				'Charcoal_ISQLBuilder'				=> 'interfaces',
				'Charcoal_ITableModel'				=> 'interfaces',
				'Charcoal_ITask'					=> 'interfaces',
				'Charcoal_ITaskManager'				=> 'interfaces',
				'Charcoal_ITokenGenerator'			=> 'interfaces',
				'Charcoal_ITransformer'				=> 'interfaces',
				'Charcoal_IValidator'				=> 'interfaces',

		// event classes
				'Charcoal_AbortEvent'							=> 'objects/events',
				'Charcoal_AuthTokenEvent'						=> 'objects/events',
				'Charcoal_Event'								=> 'objects/events',
				'Charcoal_HttpRequestEvent'						=> 'objects/events',
				'Charcoal_PermissionDeniedEvent'				=> 'objects/events',
				'Charcoal_RenderLayoutEvent'					=> 'objects/events',
				'Charcoal_SecurityFaultEvent'					=> 'objects/events',
				'Charcoal_SetupEvent'							=> 'objects/events',
				'Charcoal_SystemEvent'							=> 'objects/events',
				'Charcoal_URLRedirectEvent'						=> 'objects/events',
				'Charcoal_UserEvent'							=> 'objects/events',
				'Charcoal_TestEvent'							=> 'objects/events',

		// exception handler classes
				'Charcoal_HttpErrorDocumentExceptionHandler'	=> 'objects/exception_handlers',
				'Charcoal_HtmlFileOutputExceptionHandler'		=> 'objects/exception_handlers',
				'Charcoal_ConsoleOutputExceptionHandler'		=> 'objects/exception_handlers',

		// logger classes
				'Charcoal_BaseLogger'							=> 'objects/loggers',
				'Charcoal_CsvFileLogger'						=> 'objects/loggers',
				'Charcoal_FileLogger'							=> 'objects/loggers',
				'Charcoal_HtmlFileLogger'						=> 'objects/loggers',
				'Charcoal_ScreenLogger'							=> 'objects/loggers',
				'Charcoal_PopupScreenLogger'					=> 'objects/loggers',
				'Charcoal_ConsoleLogger'						=> 'objects/loggers',

		// exception classes
				'Charcoal_AnnotaionException'					=> 'exceptions',
				'Charcoal_AnnotaionMandatoryException'			=> 'exceptions',
				'Charcoal_ArrayFormatException'					=> 'exceptions',
				'Charcoal_BadReturnValueTypeException'			=> 'exceptions',
				'Charcoal_BooleanFormatException'				=> 'exceptions',
				'Charcoal_CacheDriverException'					=> 'exceptions',
				'Charcoal_ClassLoaderConfigException'			=> 'exceptions',
				'Charcoal_ClassNameEmptyException'				=> 'exceptions',
				'Charcoal_ComponentConfigException'				=> 'exceptions',
				'Charcoal_ComponentNotRegisteredException'		=> 'exceptions',
				'Charcoal_ConfigException'						=> 'exceptions',
				'Charcoal_ConfigFileNotFoundException'			=> 'exceptions',
				'Charcoal_ConfigNotFoundException'				=> 'exceptions',
				'Charcoal_DateFormatException'					=> 'exceptions',
				'Charcoal_DateWithTimeFormatException'			=> 'exceptions',
				'Charcoal_DataSourceConfigException'			=> 'exceptions',
				'Charcoal_DBAutoCommitException'				=> 'exceptions',
				'Charcoal_DBBeginTransactionException'			=> 'exceptions',
				'Charcoal_DBCommitTransactionException'			=> 'exceptions',
				'Charcoal_DBDataSourceException'				=> 'exceptions',
				'Charcoal_DBException'							=> 'exceptions',
				'Charcoal_DBConnectException'					=> 'exceptions',
				'Charcoal_DBRollbackTransactionException'		=> 'exceptions',
				'Charcoal_DirectoryPermissionException'			=> 'exceptions',
				'Charcoal_EmptyStackException'					=> 'exceptions',
				'Charcoal_EncodingConverterException'			=> 'exceptions',
				'Charcoal_EventConfigException'					=> 'exceptions',
				'Charcoal_FileOpenException'					=> 'exceptions',
				'Charcoal_FilterConfigException'				=> 'exceptions',
				'Charcoal_FileOutputException'					=> 'exceptions',
				'Charcoal_FileRenameException'					=> 'exceptions',
				'Charcoal_FileUploadCantWriteException'			=> 'exceptions',
				'Charcoal_FileUploadExtensionException'			=> 'exceptions',
				'Charcoal_FileUploadFormSizeException'			=> 'exceptions',
				'Charcoal_FileUploadIniSizeException'			=> 'exceptions',
				'Charcoal_FileUploadNoFileException'			=> 'exceptions',
				'Charcoal_FileUploadNoTmpDirException'			=> 'exceptions',
				'Charcoal_FileUploadPartialException'			=> 'exceptions',
				'Charcoal_FileSystemException'					=> 'exceptions',
				'Charcoal_FloatFormatException'					=> 'exceptions',
				'Charcoal_HttpException'						=> 'exceptions',
				'Charcoal_IllegalOffsetTypeException'			=> 'exceptions',
				'Charcoal_ImageGetSizeException'				=> 'exceptions',
				'Charcoal_InterfaceNotFoundException'			=> 'exceptions',
				'Charcoal_IntegerFormatException'				=> 'exceptions',
				'Charcoal_InvalidClassNameException'			=> 'exceptions',
				'Charcoal_InvalidDBRelationException'			=> 'exceptions',
				'Charcoal_InvalidEncodingCodeException'			=> 'exceptions',
				'Charcoal_InvalidHashMapKeyException'			=> 'exceptions',
				'Charcoal_InvalidMailAddressException'			=> 'exceptions',
				'Charcoal_InvalidPostParameterException'		=> 'exceptions',
				'Charcoal_LayoutNotFoundException'				=> 'exceptions',
				'Charcoal_LoggerConfigException'				=> 'exceptions',
				'Charcoal_MakeDirectoryException'				=> 'exceptions',
				'Charcoal_MakeFileException'					=> 'exceptions',
				'Charcoal_ModuleConfigException'				=> 'exceptions',
				'Charcoal_NonArrayException'					=> 'exceptions',
				'Charcoal_NonBooleanException'					=> 'exceptions',
				'Charcoal_NonIntegerException'					=> 'exceptions',
				'Charcoal_NonNumberException'					=> 'exceptions',
				'Charcoal_NonObjectException'					=> 'exceptions',
				'Charcoal_NonStringException'					=> 'exceptions',
				'Charcoal_NotImplementedException'				=> 'exceptions',
				'Charcoal_NullPointerException'					=> 'exceptions',
				'Charcoal_NumberOfArgsException'				=> 'exceptions',
				'Charcoal_ObjectPathFormatException'			=> 'exceptions',
				'Charcoal_ObjectConfigException'				=> 'exceptions',
				'Charcoal_PagerComponentException'				=> 'exceptions',
				'Charcoal_ParameterException'					=> 'exceptions',
				'Charcoal_PhpSourceParserException'				=> 'exceptions',
				'Charcoal_ProcedureConfigException'				=> 'exceptions',
				'Charcoal_ProcedureNotFoundException'			=> 'exceptions',
				'Charcoal_ProcessEventException'				=> 'exceptions',
				'Charcoal_QueryTargetException'					=> 'exceptions',
				'Charcoal_RequestConfigException'				=> 'exceptions',
				'Charcoal_SessionFileUnreadableException'		=> 'exceptions',
				'Charcoal_SessionHandlerConfigException'		=> 'exceptions',
				'Charcoal_ShellException'						=> 'exceptions',
				'Charcoal_SmartyCompileException'				=> 'exceptions',
				'Charcoal_SmartyRendererTaskException'			=> 'exceptions',
				'Charcoal_SQLBuilderConfigException'			=> 'exceptions',
				'Charcoal_SQLBuilderException'					=> 'exceptions',
				'Charcoal_StringFormatException'				=> 'exceptions',
				'Charcoal_TableModelException'					=> 'exceptions',
				'Charcoal_TableModelFieldException'				=> 'exceptions',
				'Charcoal_TableModelConfigException'			=> 'exceptions',
				'Charcoal_TaskConfigException'					=> 'exceptions',
				'Charcoal_TaskGuardConditionException'			=> 'exceptions',
				'Charcoal_TaskNotRegisteredException'			=> 'exceptions',
				'Charcoal_TemplateFileNotFoundException'		=> 'exceptions',
				'Charcoal_TransformerException'					=> 'exceptions',
				'Charcoal_TransformerConfigException'			=> 'exceptions',
				'Charcoal_UnsupportedImageFormatException'		=> 'exceptions',
				'Charcoal_RouterConfigException'				=> 'exceptions',
				'Charcoal_ValidatorConfigException'				=> 'exceptions',
				'Charcoal_XmlRenderingException'				=> 'exceptions',
				'Charcoal_PHPConfigException'					=> 'exceptions',

		// I/O classes
				'Charcoal_FileWriter'						=> 'classes/io',
				'Charcoal_RegExFileFilter'					=> 'classes/io',
				'Charcoal_WildcardFileFilter'				=> 'classes/io',
				'Charcoal_CombinedFileFilter'				=> 'classes/io',

		// config provider classes
				'Charcoal_PhpConfigProvider'				=> 'classes/config_providers',
				'Charcoal_SpycConfigProvider'				=> 'classes/config_providers',
				'Charcoal_CachedSpycConfigProvider'			=> 'classes/config_providers',

		// task manager classes
				'Charcoal_DefaultTaskManager'				=> 'objects/task_managers',

		// task classes
				'Charcoal_Task'								=> 'objects/tasks',
				'Charcoal_SmartyRendererTask'				=> 'objects/tasks',
				'Charcoal_SecureTask'						=> 'objects/tasks',
				"Charcoal_TestTask"							=> "objects/tasks",


		// xml support classes
				'Charcoal_XmlElement'						=> 'classes/xml',
				'Charcoal_XmlRenderer'						=> 'classes/xml',

		// module classes

		// core hook classes
				'Charcoal_DefaultCoreHook'					=> 'objects/core_hooks',

		// debugtrace renderer classes
				'Charcoal_HtmlDebugtraceRenderer'			=> 'objects/debugtrace_renderers',
				'Charcoal_ConsoleDebugtraceRenderer'		=> 'objects/debugtrace_renderers',
				'Charcoal_LogDebugtraceRenderer'			=> 'objects/debugtrace_renderers',

		// table model classes
				'Charcoal_AnnotaionTableModel'				=> 'objects/table_models',
				'Charcoal_DefaultTableModel'				=> 'objects/table_models',
				'Charcoal_SessionTableModel'				=> 'objects/table_models',

		// DTO classes
				'Charcoal_TableDTO'							=> 'objects/DTOs',

		// data source classes
				'Charcoal_PearDbDataSource'					=> 'objects/data_sources',
				'Charcoal_PDODbDataSource'					=> 'objects/data_sources',

		// request classes
				'Charcoal_ShellRequest'						=> 'objects/requests',
				'Charcoal_HttpRequest'						=> 'objects/requests',

		// response classes
				'Charcoal_ShellResponse'					=> 'objects/responses',
				'Charcoal_HttpResponse'						=> 'objects/responses',

		// session hanlder classes
				'Charcoal_DefaultSessionHandler'			=> 'objects/session_handlers',
				'Charcoal_SmartGatewaySessionHandler'		=> 'objects/session_handlers',

		// SQL Builder classes
				'Charcoal_DefaultSQLBuilder'				=> 'objects/sql_builders',
				'Charcoal_MySQL_SQLBuilder'					=> 'objects/sql_builders',
				'Charcoal_PostgreSQL_SQLBuilder'			=> 'objects/sql_builders',

		// procedure classes
				'Charcoal_HttpProcedure'					=> 'objects/procedures',
				'Charcoal_SimpleProcedure'					=> 'objects/procedures',

		// component classes
				'Charcoal_Linker'							=> 'components/charcoal',
				'Charcoal_BreadcrumbList'					=> 'components/charcoal',
				'Charcoal_Calendar'							=> 'components/charcoal',
				'Charcoal_Pager'							=> 'components/charcoal',
				'Charcoal_SmartGateway'						=> 'components/charcoal/db',
				'Charcoal_SQLCriteria'						=> 'components/charcoal/db',
				'Charcoal_PagedSQLCriteria'					=> 'components/charcoal/db',
				'Charcoal_QueryTarget'						=> 'components/charcoal/db',
				'Charcoal_QueryTargetElement'				=> 'components/charcoal/db',
				'Charcoal_SelectContext'					=> 'components/charcoal/db',
				'Charcoal_FromContext'						=> 'components/charcoal/db',
				'Charcoal_WhereContext'						=> 'components/charcoal/db',
				'Charcoal_OrderByContext'					=> 'components/charcoal/db',
				'Charcoal_LimitContext'						=> 'components/charcoal/db',
				'Charcoal_OffsetContext'					=> 'components/charcoal/db',
				'Charcoal_GroupByContext'					=> 'components/charcoal/db',
				'Charcoal_PreparedContext'					=> 'components/charcoal/db',
				'Charcoal_BindedContext'					=> 'components/charcoal/db',
				'Charcoal_ResultContext'					=> 'components/charcoal/db',
				'Charcoal_QueryContext'						=> 'components/charcoal/db',
				'Charcoal_JapanesePrefectureList'			=> 'components/charcoal/list',
				'Charcoal_CharcoalUnitTest'					=> 'components/charcoal/test',
				'Charcoal_CharcoalMail'						=> 'components/charcoal/mail',
				'Charcoal_QdmailSender'						=> 'components/qdmail',
				'Charcoal_QdmailAddress'					=> 'components/qdmail',
				'Charcoal_SmartyRenderer'					=> 'components/smarty',
				'Charcoal_CookieComponent'					=> 'components/charcoal/http',
				'Charcoal_FileSystemComponent'				=> 'components/charcoal/file',
				'Charcoal_TempDirComponent'					=> 'components/charcoal/file',
				'Charcoal_TempFileComponent'				=> 'components/charcoal/file',
				'Charcoal_FormTokenComponent'				=> 'components/charcoal/form',
				'Charcoal_PDFWriterComponent'				=> 'components/pdf',

		// transformer classes
				'Charcoal_SimpleTransformer'				=> 'objects/transformers',

		// layout classes
				'Charcoal_ProcedureRedirectLayout'			=> 'objects/layouts',
				'Charcoal_URLRedirectLayout'				=> 'objects/layouts',

		// service classes
				'Charcoal_HtmlEscapeResponseFilter'			=> 'objects/response_filters',

		// Router classes
				'Charcoal_SimpleRouter'						=> 'objects/routers',

		// Routing rules classes
				'Charcoal_ArrayRoutingRule'					=> 'objects/routing_rules',

		// Token genertor classes
				'Charcoal_SimpleTokenGenerator'				=> 'objects/token_generators',

		// Cache Driver classes
				'Charcoal_MemcachedCacheDriver'				=> 'objects/cache_drivers',
				'Charcoal_MemcacheCacheDriver'				=> 'objects/cache_drivers',
				'Charcoal_FileCacheDriver'					=> 'objects/cache_drivers',

		);

		return self::$class_paths;
	}

	/*
	 * クラスをロード
	 */
	public function loadClass( Charcoal_String $class_name )
	{
//		log_info( "system,debug,class_loader", "class_loader", "[FrameworkClassLoader] FrameworkClassLoader#loadClass() called: class_name=[$class_name]" );

		$class_name = us($class_name);

//print "loadClass($class_name)<br>";

		$class_paths = self::$class_paths;
		if ( !$class_paths ){
			// クラスパスの初期化
			$class_paths = self::initClassPath();
		}

		// フレームワークのクラスではない場合はFALSEを返却
		if ( !isset($class_paths[ $class_name ]) ){
//			log_info( "system,debug,class_loader", "class_loader", "[FrameworkClassLoader] Can not load class: [$class_name]" );
			return FALSE;
		}

		// クラス名からクラスパスを取得
		$class_path = isset($class_paths[ $class_name ]) ? $class_paths[ $class_name ] : NULL;
		if ( $class_path === NULL ){
			_throw( new Charcoal_ClassPathNotFoundException( $class_name ) );
		}
		$file_name = $class_name . CHARCOAL_CLASS_FILE_SUFFIX;
		$pos = strpos( $file_name, CHARCOAL_CLASS_PREFIX );
		if ( $pos !== FALSE ){
			$file_name = substr( $file_name, $pos + strlen(CHARCOAL_CLASS_PREFIX) );
		}
		$class_path = CHARCOAL_HOME . '/src/' . $class_path . '/' . $file_name;
//		log_info( "system,debug,class_loader", "class_loader", "[FrameworkClassLoader] class_path=[$class_path] class_name=[$class_name]" );

		// ソース読み込み
		Charcoal_Framework::loadSourceFile( new Charcoal_File(s($class_path)) );

//		log_info( "system,debug,class_loader", "class_loader", "[FrameworkClassLoader] Class file loaded: class=[$class_name] file=[$class_path]" );

		return TRUE;
	}
}
return __FILE__;

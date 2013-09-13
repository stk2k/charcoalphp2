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
	static $class_paths = array(

		// base classes
				'Charcoal_DTO'						=> 'classes/base',
				'Charcoal_CharcoalComponent'		=> 'classes/base',
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

		// core classes
				'Charcoal_AnnotationValue'			=> 'classes/core',
				'Charcoal_CookieReader'				=> 'classes/core',
				'Charcoal_CookieWriter'				=> 'classes/core',
				'Charcoal_EventContext'				=> 'classes/core',
				'Charcoal_EventQueue'				=> 'classes/core',
				'Charcoal_HttpHeader'				=> 'classes/core',
				'Charcoal_Layout'					=> 'classes/core',
				'Charcoal_ModuleLoader'				=> 'classes/core',
				'Charcoal_Sequence'					=> 'classes/core',
				'Charcoal_SequenceHolder'			=> 'classes/core',
				'Charcoal_Session'					=> 'classes/core',
				'Charcoal_SimpleModule'				=> 'classes/core',
				'Charcoal_TransformerCache'			=> 'classes/core',

		// utility classes
				'Charcoal_CommandLineUtil'			=> 'classes/util',
				'Charcoal_DBPageInfo'				=> 'classes/util',
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
				'Charcoal_IDataSource'				=> 'interfaces',
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
				'Charcoal_ExceptionEvent'						=> 'objects/events',
				'Charcoal_HttpRequestEvent'						=> 'objects/events',
				'Charcoal_PermissionDeniedEvent'				=> 'objects/events',
				'Charcoal_RenderLayoutEvent'					=> 'objects/events',
				'Charcoal_SecurityFaultEvent'					=> 'objects/events',
				'Charcoal_SetupEvent'							=> 'objects/events',
				'Charcoal_SystemEvent'							=> 'objects/events',
				'Charcoal_URLRedirectEvent'						=> 'objects/events',
				'Charcoal_UserEvent'							=> 'objects/events',
				'Charcoal_RequestEvent'							=> 'objects/events',
				'Charcoal_TestEvent'							=> 'objects/events',

		// exception classes
				'Charcoal_AnnotaionException'					=> 'exceptions',
				'Charcoal_BadExitCodeException'					=> 'exceptions',
				'Charcoal_BenchmarkException'					=> 'exceptions',
				'Charcoal_CacheDriverException'					=> 'exceptions',
				'Charcoal_ComponentConfigException'				=> 'exceptions',
				'Charcoal_ComponentNotRegisteredException'		=> 'exceptions',
				'Charcoal_ConfigException'						=> 'exceptions',
				'Charcoal_ConfigFileNotFoundException'			=> 'exceptions',
				'Charcoal_DateFormatException'					=> 'exceptions',
				'Charcoal_DBAutoCommitException'				=> 'exceptions',
				'Charcoal_DBBeginTransactionException'			=> 'exceptions',
				'Charcoal_DBCommitTransactionException'			=> 'exceptions',
				'Charcoal_DBDataSourceException'				=> 'exceptions',
				'Charcoal_DBException'							=> 'exceptions',
				'Charcoal_DBConnectException'					=> 'exceptions',
				'Charcoal_DBRollbackTransactionException'		=> 'exceptions',
				'Charcoal_EventContextException'				=> 'exceptions',
				'Charcoal_FileOpenException'					=> 'exceptions',
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
				'Charcoal_HttpException'						=> 'exceptions',
				'Charcoal_ImageGetSizeException'				=> 'exceptions',
				'Charcoal_InterfaceNotFoundException'			=> 'exceptions',
				'Charcoal_InvalidEncodingCodeException'			=> 'exceptions',
				'Charcoal_InvalidMailAddressException'			=> 'exceptions',
				'Charcoal_LoggerConfigException'				=> 'exceptions',
				'Charcoal_MakeDirectoryException'				=> 'exceptions',
				'Charcoal_MakeFileException'					=> 'exceptions',
				'Charcoal_NonArrayException'					=> 'exceptions',
				'Charcoal_NonBooleanException'					=> 'exceptions',
				'Charcoal_NonIntegerException'					=> 'exceptions',
				'Charcoal_NonNumberException'					=> 'exceptions',
				'Charcoal_NonObjectException'					=> 'exceptions',
				'Charcoal_NonStringException'					=> 'exceptions',
				'Charcoal_ObjectPathFormatException'			=> 'exceptions',
				'Charcoal_RoutingRuletConfigException'			=> 'exceptions',
				'Charcoal_PhpSourceParserException'				=> 'exceptions',
				'Charcoal_ProcedureNotFoundException'			=> 'exceptions',
				'Charcoal_ProcessEventException'				=> 'exceptions',
				'Charcoal_QueryTargetException'					=> 'exceptions',
				'Charcoal_SessionHandlerException'				=> 'exceptions',
				'Charcoal_SmartyRendererTaskException'			=> 'exceptions',
				'Charcoal_StackEmptyException'					=> 'exceptions',
				'Charcoal_SQLBuilderException'					=> 'exceptions',
				'Charcoal_TableModelException'					=> 'exceptions',
				'Charcoal_TableModelFieldException'				=> 'exceptions',
				'Charcoal_TaskNotFoundException'				=> 'exceptions',
				'Charcoal_URLFormatException'					=> 'exceptions',

		// I/O classes
				'Charcoal_FileWriter'						=> 'classes/io',
				'Charcoal_AbstractFileFilter'					=> 'classes/io',
				'Charcoal_RegExFileFilter'					=> 'classes/io',
				'Charcoal_WildcardFileFilter'				=> 'classes/io',
				'Charcoal_CombinedFileFilter'				=> 'classes/io',

		// task manager classes
				'Charcoal_AbstractTaskManager'					=> 'objects/task_managers',
				'Charcoal_DefaultTaskManager'				=> 'objects/task_managers',

		// task classes
				'Charcoal_Task'								=> 'objects/tasks',
				'Charcoal_SmartyRendererTask'				=> 'objects/tasks',
				'Charcoal_SecureTask'						=> 'objects/tasks',
				"Charcoal_TestTask"							=> "objects/tasks",

		// module classes

		// table model classes
				'Charcoal_AnnotaionTableModel'				=> 'objects/table_models',
				'Charcoal_DefaultTableModel'				=> 'objects/table_models',
				'Charcoal_SessionTableModel'				=> 'objects/table_models',

		// DTO classes
				'Charcoal_TableDTO'							=> 'objects/DTOs',
				'Charcoal_SessionTableDTO'					=> 'objects/DTOs',

		// data source classes
				'Charcoal_AbstractDataSource'					=> 'objects/data_sources',
				'Charcoal_PearDbDataSource'					=> 'objects/data_sources',
				'Charcoal_PDODbDataSource'					=> 'objects/data_sources',

		// request classes
				'Charcoal_AbstractRequest'						=> 'objects/requests',
				'Charcoal_ShellRequest'						=> 'objects/requests',
				'Charcoal_HttpRequest'						=> 'objects/requests',

		// response classes
				'Charcoal_AbstractResponse'						=> 'objects/responses',
				'Charcoal_ShellResponse'					=> 'objects/responses',
				'Charcoal_HttpResponse'						=> 'objects/responses',

		// session hanlder classes
				'Charcoal_AbstractSessionHandler'				=> 'objects/session_handlers',
				'Charcoal_DefaultSessionHandler'			=> 'objects/session_handlers',
				'Charcoal_SmartGatewaySessionHandler'		=> 'objects/session_handlers',

		// SQL Builder classes
				'Charcoal_AbstractSQLBuilder'					=> 'objects/sql_builders',
				'Charcoal_MySQL_SQLBuilder'					=> 'objects/sql_builders',
				'Charcoal_PostgreSQL_SQLBuilder'			=> 'objects/sql_builders',

		// procedure classes
				'Charcoal_AbstractProcedure'					=> 'objects/procedures',
				'Charcoal_HttpProcedure'					=> 'objects/procedures',
				'Charcoal_SimpleProcedure'					=> 'objects/procedures',

		// component classes
				'Charcoal_Linker'							=> 'components/charcoal',
				'Charcoal_BreadcrumbList'					=> 'components/charcoal',
				'Charcoal_Calendar'							=> 'components/charcoal',
				'Charcoal_Pager'							=> 'components/charcoal',
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

		// component classes(smart gateway)
				'Charcoal_SmartGateway'						=> 'components/charcoal/db',
				'Charcoal_SQLCriteria'						=> 'components/charcoal/db',
				'Charcoal_PagedSQLCriteria'					=> 'components/charcoal/db',
				'Charcoal_QueryJoin'						=> 'components/charcoal/db',
				'Charcoal_QueryTarget'						=> 'components/charcoal/db',
				'Charcoal_QueryTargetElement'				=> 'components/charcoal/db',

				'Charcoal_AbstractWrapperContext'				=> 'components/charcoal/db/context',
				'Charcoal_SelectContext'					=> 'components/charcoal/db/context',
				'Charcoal_FromContext'						=> 'components/charcoal/db/context',
				'Charcoal_JoinContext'						=> 'components/charcoal/db/context',
				'Charcoal_WhereContext'						=> 'components/charcoal/db/context',
				'Charcoal_OrderByContext'					=> 'components/charcoal/db/context',
				'Charcoal_LimitContext'						=> 'components/charcoal/db/context',
				'Charcoal_OffsetContext'					=> 'components/charcoal/db/context',
				'Charcoal_GroupByContext'					=> 'components/charcoal/db/context',
				'Charcoal_PreparedContext'					=> 'components/charcoal/db/context',
				'Charcoal_BindedContext'					=> 'components/charcoal/db/context',
				'Charcoal_ResultContext'					=> 'components/charcoal/db/context',
				'Charcoal_QueryContext'						=> 'components/charcoal/db/context',

		// transformer classes
				'Charcoal_AbstractTransformer'					=> 'objects/transformers',
				'Charcoal_SimpleTransformer'				=> 'objects/transformers',

		// layout classes
				'Charcoal_AbstractLayout'						=> 'objects/layouts',
				'Charcoal_ProcedureRedirectLayout'			=> 'objects/layouts',
				'Charcoal_URLRedirectLayout'				=> 'objects/layouts',

		// service classes
				'Charcoal_AbstractResponseFilter'				=> 'objects/response_filters',
				'Charcoal_HtmlEscapeResponseFilter'			=> 'objects/response_filters',

		// Router classes
				'Charcoal_AbstractRouter'						=> 'objects/routers',
				'Charcoal_SimpleRouter'						=> 'objects/routers',

		// Routing rules classes
				'Charcoal_AbstractRoutingRule'					=> 'objects/routing_rules',
				'Charcoal_ArrayRoutingRule'					=> 'objects/routing_rules',

		// Token genertor classes
				'Charcoal_AbstractTokenGenerator'				=> 'objects/token_generators',
				'Charcoal_SimpleTokenGenerator'				=> 'objects/token_generators',

		// Cache Driver classes
				'Charcoal_AbstractCacheDriver'					=> 'objects/cache_drivers',
				'Charcoal_MemcachedCacheDriver'				=> 'objects/cache_drivers',
				'Charcoal_MemcacheCacheDriver'				=> 'objects/cache_drivers',
				'Charcoal_FileCacheDriver'					=> 'objects/cache_drivers',

		);

	/**
	 *	constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/*
	 * クラスをロード
	 */
	public function loadClass( $class_name )
	{
//		log_info( "system,debug,class_loader", "class_loader", "[FrameworkClassLoader] FrameworkClassLoader#loadClass() called: class_name=[$class_name]" );

		$class_name = us($class_name);

//print "loadClass($class_name)<br>";

		$class_paths = self::$class_paths;

		// フレームワークのクラスではない場合はFALSEを返却
		if ( !isset($class_paths[ $class_name ]) ){
//			log_info( "system,debug,class_loader", "class_loader", "[FrameworkClassLoader] Can not load class: [$class_name]" );
			return FALSE;
		}

		// クラス名からクラスパスを取得
		$file_name = $class_name . CHARCOAL_CLASS_FILE_SUFFIX;
		$pos = strpos( $file_name, CHARCOAL_CLASS_PREFIX );
		if ( $pos !== FALSE ){
			$file_name = substr( $file_name, $pos + strlen(CHARCOAL_CLASS_PREFIX) );
		}
		$class_path = CHARCOAL_HOME . '/src/' . $class_paths[ $class_name ] . '/' . $file_name;
//		log_info( "system,debug,class_loader", "class_loader", "[FrameworkClassLoader] class_path=[$class_path] class_name=[$class_name]" );

		// ソース読み込み
		Charcoal_Framework::loadSourceFile( $class_path );

//		log_info( "system,debug,class_loader", "class_loader", "[FrameworkClassLoader] Class file loaded: class=[$class_name] file=[$class_path]" );

		return TRUE;
	}
}


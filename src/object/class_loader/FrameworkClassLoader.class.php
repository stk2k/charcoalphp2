<?php
/**
* フレームワーク用クラスローダ
*
* PHP version 5
*
* @package    objects.class_loaders
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_FrameworkClassLoader extends Charcoal_CharcoalObject implements Charcoal_IClassLoader
{
	static $class_paths = array(

		// base classes
				'Charcoal_DTO'						=> 'class/base',
				'Charcoal_CharcoalComponent'		=> 'class/base',
				'Charcoal_ImageFile'				=> 'class/base',
				'Charcoal_Position'					=> 'class/base',
				'Charcoal_PositionFloat'			=> 'class/base',
				'Charcoal_Rectangle'				=> 'class/base',
				'Charcoal_RectangleFloat'			=> 'class/base',
				'Charcoal_URL'						=> 'class/base',

		// enum constant classes
				'Charcoal_EnumSmtpStatusCode'		=> 'constant',
				'Charcoal_EnumEventPriority'		=> 'constant',
				'Charcoal_EnumHttpMethod'			=> 'constant',
				'Charcoal_EnumSQLJoinType'			=> 'constant',
				'Charcoal_EnumSQLAggregateFunc'		=> 'constant',

		// core classes
				'Charcoal_AnnotationValue'			=> 'class/core',
				'Charcoal_CookieReader'				=> 'class/core',
				'Charcoal_CookieWriter'				=> 'class/core',
				'Charcoal_EventContext'				=> 'class/core',
				'Charcoal_EventQueue'				=> 'class/core',
				'Charcoal_HttpHeader'				=> 'class/core',
				'Charcoal_Layout'					=> 'class/core',
				'Charcoal_ModuleLoader'				=> 'class/core',
				'Charcoal_Sequence'					=> 'class/core',
				'Charcoal_SequenceHolder'			=> 'class/core',
				'Charcoal_Session'					=> 'class/core',
				'Charcoal_SimpleModule'				=> 'class/core',
				'Charcoal_TransformerCache'			=> 'class/core',

		// utility classes
				'Charcoal_CommandLineUtil'			=> 'class/util',
				'Charcoal_DBPageInfo'				=> 'class/util',
				'Charcoal_UploadedFile'				=> 'class/util',
				'Charcoal_FileSystemUtil'			=> 'class/util',
				'Charcoal_GraphicsUtil'				=> 'class/util',
				'Charcoal_MailUtil'					=> 'class/util',
				'Charcoal_SQLUtil'					=> 'class/util',
				'Charcoal_URLUtil'					=> 'class/util',
				'Charcoal_XmlUtil'					=> 'class/util',
				'Charcoal_ErrorReportingSwitcher'	=> 'class/util',

		// interface classes
				'Charcoal_ICacheDriver'				=> 'interface',
				'Charcoal_ICharcoalObject'			=> 'interface',
				'Charcoal_IClassLoader'				=> 'interface',
				'Charcoal_IComponent'				=> 'interface',
				'Charcoal_IDataSource'				=> 'interface',
				'Charcoal_IEvent'					=> 'interface',
				'Charcoal_IEventContext'			=> 'interface',
				'Charcoal_IExceptionHandler'		=> 'interface',
				'Charcoal_IFileFilter'				=> 'interface',
				'Charcoal_IHashable'				=> 'interface',
				'Charcoal_ILayoutManager'			=> 'interface',
				'Charcoal_ILogger'					=> 'interface',
				'Charcoal_IModel'					=> 'interface',
				'Charcoal_IModule'					=> 'interface',
				'Charcoal_IProcedure'				=> 'interface',
				'Charcoal_IProperties'				=> 'interface',
				'Charcoal_IRedirectLayout'			=> 'interface',
				'Charcoal_IRequest'					=> 'interface',
				'Charcoal_IResponse'				=> 'interface',
				'Charcoal_IResponseFilter'			=> 'interface',
				'Charcoal_IRouter'					=> 'interface',
				'Charcoal_IRoutingRule'				=> 'interface',
				'Charcoal_ISessionHandler'			=> 'interface',
				'Charcoal_IStateful'				=> 'interface',
				'Charcoal_ISequence'				=> 'interface',
				'Charcoal_ISQLBuilder'				=> 'interface',
				'Charcoal_ITableModel'				=> 'interface',
				'Charcoal_ITask'					=> 'interface',
				'Charcoal_ITaskManager'				=> 'interface',
				'Charcoal_ITokenGenerator'			=> 'interface',
				'Charcoal_ITransformer'				=> 'interface',
				'Charcoal_IValidator'				=> 'interface',

		// event classes
				'Charcoal_AbortEvent'							=> 'object/event',
				'Charcoal_AuthTokenEvent'						=> 'object/event',
				'Charcoal_Event'								=> 'object/event',
				'Charcoal_ExceptionEvent'						=> 'object/event',
				'Charcoal_HttpRequestEvent'						=> 'object/event',
				'Charcoal_PermissionDeniedEvent'				=> 'object/event',
				'Charcoal_RenderEvent'							=> 'object/event',
				'Charcoal_RenderLayoutEvent'					=> 'object/event',
				'Charcoal_SecurityFaultEvent'					=> 'object/event',
				'Charcoal_SetupEvent'							=> 'object/event',
				'Charcoal_SystemEvent'							=> 'object/event',
				'Charcoal_URLRedirectEvent'						=> 'object/event',
				'Charcoal_UserEvent'							=> 'object/event',
				'Charcoal_RequestEvent'							=> 'object/event',
				'Charcoal_TestEvent'							=> 'object/event',
				'Charcoal_ShellCommandEvent'					=> 'object/event',

		// exception classes
				'Charcoal_AnnotaionException'					=> 'exception',
				'Charcoal_BadExitCodeException'					=> 'exception',
				'Charcoal_BenchmarkException'					=> 'exception',
				'Charcoal_CacheDriverException'					=> 'exception',
				'Charcoal_ComponentConfigException'				=> 'exception',
				'Charcoal_ComponentNotRegisteredException'		=> 'exception',
				'Charcoal_ConfigException'						=> 'exception',
				'Charcoal_ConfigFileNotFoundException'			=> 'exception',
				'Charcoal_DBAutoCommitException'				=> 'exception',
				'Charcoal_DBBeginTransactionException'			=> 'exception',
				'Charcoal_DBCommitTransactionException'			=> 'exception',
				'Charcoal_DBDataSourceException'				=> 'exception',
				'Charcoal_DBException'							=> 'exception',
				'Charcoal_DBConnectException'					=> 'exception',
				'Charcoal_DBRollbackTransactionException'		=> 'exception',
				'Charcoal_EventContextException'				=> 'exception',
				'Charcoal_EventLoopCounterOverflowException'	=> 'exception',
				'Charcoal_ExtensionNotLoadedException'			=> 'exception',
				'Charcoal_FileOpenException'					=> 'exception',
				'Charcoal_FileOutputException'					=> 'exception',
				'Charcoal_FileRenameException'					=> 'exception',
				'Charcoal_FileUploadCantWriteException'			=> 'exception',
				'Charcoal_FileUploadExtensionException'			=> 'exception',
				'Charcoal_FileUploadFormSizeException'			=> 'exception',
				'Charcoal_FileUploadIniSizeException'			=> 'exception',
				'Charcoal_FileUploadNoFileException'			=> 'exception',
				'Charcoal_FileUploadNoTmpDirException'			=> 'exception',
				'Charcoal_FileUploadPartialException'			=> 'exception',
				'Charcoal_FileSystemException'					=> 'exception',
				'Charcoal_HttpStatusException'					=> 'exception',
				'Charcoal_ImageGetSizeException'				=> 'exception',
				'Charcoal_InterfaceNotFoundException'			=> 'exception',
				'Charcoal_InvalidArgumentException'				=> 'exception',
				'Charcoal_InvalidEncodingCodeException'			=> 'exception',
				'Charcoal_InvalidMailAddressException'			=> 'exception',
				'Charcoal_JsonDecodingException'				=> 'exception',
				'Charcoal_LayoutManagerCreationException'		=> 'exception',
				'Charcoal_LoggerConfigException'				=> 'exception',
				'Charcoal_MakeDirectoryException'				=> 'exception',
				'Charcoal_MakeFileException'					=> 'exception',
				'Charcoal_NonArrayException'					=> 'exception',
				'Charcoal_NonBooleanException'					=> 'exception',
				'Charcoal_NonIntegerException'					=> 'exception',
				'Charcoal_NonNumberException'					=> 'exception',
				'Charcoal_NonObjectException'					=> 'exception',
				'Charcoal_NonStringException'					=> 'exception',
				'Charcoal_NotSupportedOperationException'		=> 'exception',
				'Charcoal_ObjectPathFormatException'			=> 'exception',
				'Charcoal_ProfileConfigException'				=> 'exception',
				'Charcoal_RoutingRuletConfigException'			=> 'exception',
				'Charcoal_RoutingRuleSyntaxErrorException'		=> 'exception',
				'Charcoal_PhpSourceParserException'				=> 'exception',
				'Charcoal_ProcedureNotFoundException'			=> 'exception',
				'Charcoal_ProcessEventAtTaskException'			=> 'exception',
				'Charcoal_ProcessEventAtTaskManagerException'	=> 'exception',
				'Charcoal_QueryTargetException'					=> 'exception',
				'Charcoal_SessionHandlerException'				=> 'exception',
				'Charcoal_SmartyRendererTaskException'			=> 'exception',
				'Charcoal_StackEmptyException'					=> 'exception',
				'Charcoal_SQLBuilderException'					=> 'exception',
				'Charcoal_TableModelException'					=> 'exception',
				'Charcoal_TableModelFieldException'				=> 'exception',
				'Charcoal_TestDataNotFoundException'			=> 'exception',
				'Charcoal_TaskNotFoundException'				=> 'exception',
				'Charcoal_UnsupportedImageFormatException'		=> 'exception',
				'Charcoal_URLFormatException'					=> 'exception',
				'Charcoal_ComponentLoadingException'			=> 'exception',

		// I/O classes
				'Charcoal_FileWriter'						=> 'class/io',
				'Charcoal_AbstractFileFilter'					=> 'class/io',
				'Charcoal_RegExFileFilter'					=> 'class/io',
				'Charcoal_WildcardFileFilter'				=> 'class/io',
				'Charcoal_CombinedFileFilter'				=> 'class/io',

		// task manager classes
				'Charcoal_AbstractTaskManager'					=> 'object/task_manager',
				'Charcoal_DefaultTaskManager'				=> 'object/task_manager',

		// task classes
				'Charcoal_Task'								=> 'object/task',
				'Charcoal_SmartyRendererTask'				=> 'object/task',
				'Charcoal_SecureTask'						=> 'object/task',
				"Charcoal_TestTask"							=> 'object/task',

		// module classes

		// table model classes
				'Charcoal_AnnotaionTableModel'				=> 'object/table_model',
				'Charcoal_DefaultTableModel'				=> 'object/table_model',
				'Charcoal_SessionTableModel'				=> 'object/table_model',

		// DTO classes
				'Charcoal_TableDTO'							=> 'object/dto',
				'Charcoal_SessionTableDTO'					=> 'object/dto',

		// data source classes
				'Charcoal_AbstractDataSource'				=> 'object/data_source',
				'Charcoal_PearDbDataSource'					=> 'object/data_source',
				'Charcoal_PDODbDataSource'					=> 'object/data_source',
				'Charcoal_SQLiteDataSource'					=> 'object/data_source',

		// request classes
				'Charcoal_AbstractRequest'					=> 'object/request',
				'Charcoal_ShellRequest'						=> 'object/request',
				'Charcoal_HttpRequest'						=> 'object/request',

		// response classes
				'Charcoal_AbstractResponse'					=> 'object/response',
				'Charcoal_ShellResponse'					=> 'object/response',
				'Charcoal_HttpResponse'						=> 'object/response',

		// session hanlder classes
				'Charcoal_AbstractSessionHandler'			=> 'object/session_handler',
				'Charcoal_DefaultSessionHandler'			=> 'object/session_handler',
				'Charcoal_SmartGatewaySessionHandler'		=> 'object/session_handler',

		// SQL Builder classes
				'Charcoal_AbstractSQLBuilder'				=> 'object/sql_builder',
				'Charcoal_MySQL_SQLBuilder'					=> 'object/sql_builder',
				'Charcoal_PostgreSQL_SQLBuilder'			=> 'object/sql_builder',

		// procedure classes
				'Charcoal_AbstractProcedure'				=> 'object/procedure',
				'Charcoal_HttpProcedure'					=> 'object/procedure',
				'Charcoal_SimpleProcedure'					=> 'object/procedure',

		// component classes
				'Charcoal_QdmailSender'						=> 'component/qdmail',
				'Charcoal_QdmailAddress'					=> 'component/qdmail',
				'Charcoal_SmartyRenderer'					=> 'component/smarty',
				'Charcoal_CookieComponent'					=> 'component/charcoal/http',
				'Charcoal_FileSystemComponent'				=> 'component/charcoal/file',
				'Charcoal_TempDirComponent'					=> 'component/charcoal/file',
				'Charcoal_TempFileComponent'				=> 'component/charcoal/file',
				'Charcoal_FormTokenComponent'				=> 'component/charcoal/form',
				"Charcoal_ThumbnailComponent"				=> 'component/charcoal/thumb',
				'Charcoal_PDFWriterComponent'				=> 'component/pdf',
				'Charcoal_SimplePieComponent'				=> 'component/rss/simplepie',
				'Charcoal_FeedCreatorComponent'				=> 'component/rss/feedcreator',
				'Charcoal_SimpleHtmlDomComponent'			=> 'component/html/parser/simplehtmldom',
				'Charcoal_TidyComponent'					=> 'component/html/repair/tidy',
				'Charcoal_PhpXmlParserComponent'			=> 'component/xml/parser/php',
				'Charcoal_PhpXmlElementHandler'				=> 'component/xml/parser/php',
				'Charcoal_PearPagerComponent'				=> 'component/pear/pager',

		// component classes(smart gateway)
				'Charcoal_SmartGateway'						=> 'component/charcoal/db',
				'Charcoal_SmartGatewayImpl'					=> 'component/charcoal/db',
				'Charcoal_SQLCriteria'						=> 'component/charcoal/db',
				'Charcoal_ExecutedSQL'						=> 'component/charcoal/db',
				'Charcoal_PagedSQLCriteria'					=> 'component/charcoal/db',
				'Charcoal_QueryJoin'						=> 'component/charcoal/db',
				'Charcoal_QueryTarget'						=> 'component/charcoal/db',
				'Charcoal_QueryTargetElement'				=> 'component/charcoal/db',

				'Charcoal_AbstractWrapperContext'				=> 'component/charcoal/db/context',
				'Charcoal_SelectContext'					=> 'component/charcoal/db/context',
				'Charcoal_FromContext'						=> 'component/charcoal/db/context',
				'Charcoal_JoinContext'						=> 'component/charcoal/db/context',
				'Charcoal_WhereContext'						=> 'component/charcoal/db/context',
				'Charcoal_OrderByContext'					=> 'component/charcoal/db/context',
				'Charcoal_LimitContext'						=> 'component/charcoal/db/context',
				'Charcoal_OffsetContext'					=> 'component/charcoal/db/context',
				'Charcoal_GroupByContext'					=> 'component/charcoal/db/context',
				'Charcoal_PreparedContext'					=> 'component/charcoal/db/context',
				'Charcoal_BindedContext'					=> 'component/charcoal/db/context',
				'Charcoal_ResultContext'					=> 'component/charcoal/db/context',
				'Charcoal_QueryContext'						=> 'component/charcoal/db/context',

		// transformer classes
				'Charcoal_AbstractTransformer'				=> 'object/transformer',
				'Charcoal_SimpleTransformer'				=> 'object/transformer',

		// layout classes
				'Charcoal_AbstractLayout'					=> 'object/layout',
				'Charcoal_ProcedureRedirectLayout'			=> 'object/layout',
				'Charcoal_URLRedirectLayout'				=> 'object/layout',

		// response filter classes
				'Charcoal_AbstractResponseFilter'			=> 'object/response_filter',
				'Charcoal_HtmlEscapeResponseFilter'			=> 'object/response_filter',
				'Charcoal_StripTagsResponseFilter'			=> 'object/response_filter',

		// Router classes
				'Charcoal_AbstractRouter'					=> 'object/router',
				'Charcoal_SimpleRouter'						=> 'object/router',

		// Routing rules classes
				'Charcoal_AbstractRoutingRule'				=> 'object/routing_rule',
				'Charcoal_ArrayRoutingRule'					=> 'object/routing_rule',

		// Token genertor classes
				'Charcoal_AbstractTokenGenerator'			=> 'object/token_generator',
				'Charcoal_SimpleTokenGenerator'				=> 'object/token_generator',

		// Cache Driver classes
				'Charcoal_AbstractCacheDriver'				=> 'object/cache_driver',
				'Charcoal_MemcachedCacheDriver'				=> 'object/cache_driver',
				'Charcoal_MemcacheCacheDriver'				=> 'object/cache_driver',
				'Charcoal_FileCacheDriver'					=> 'object/cache_driver',

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
		$file_name = $class_name . '.class.php';
		$pos = strpos( $file_name, 'Charcoal_' );
		if ( $pos !== FALSE ){
			$file_name = substr( $file_name, $pos + 9 /*= strlen('Charcoal_') */ );
		}
		$class_path = CHARCOAL_HOME . '/src/' . $class_paths[ $class_name ] . '/' . $file_name;
//		log_info( "system,debug,class_loader", "class_loader", "[FrameworkClassLoader] class_path=[$class_path] class_name=[$class_name]" );

		// ソース読み込み
		Charcoal_Framework::loadSourceFile( $class_path );

//		log_info( "system,debug,class_loader", "class_loader", "[FrameworkClassLoader] Class file loaded: class=[$class_name] file=[$class_path]" );

		return TRUE;
	}
}


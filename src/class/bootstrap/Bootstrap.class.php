<?php
/**
 * CharcoalPHP ver 2.9.6
 * E-Mail for multibyte charset
 *
 * PHP versions 5 and 6 (PHP5.2 upper)
 *
 * Copyright 2013, stk2k in japan
 * Technical  :  http://charcoalphp.org/
 * Licensed under The MIT License License
 *
 * @copyright        2008 stk2k, sazysoft
 * @link            http://charcoalphp.org/
 * @version            2.9.6
 * @lastmodified    2013-04-26
 * @license            http://www.opensource.org/licenses/mit-license.php The MIT License
 * 
 * CharcoalPHP is a task-oriented web framework.
 * 
 * Copyright (C) 2013   stk2k 
 */

/**
 *    class : Charcoal_Bootstrap
 *
 *    Initialize minimum set of framework funtions(class loader, logger, DI container)
*
* PHP version 5
*
* @package    class.bootstrap
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_Bootstrap
{
    static $debug;

    /*
     *    Framework global error handler
     */
    public static function onUnhandledError( $errno, $errstr, $errfile, $errline )
    {
        $flags_handled = error_reporting() ;
        if ( Charcoal_System::isBitSet( $errno, $flags_handled, Charcoal_System::BITTEST_MODE_ANY ) )
        {
            // create fake exception
            $e = new Charcoal_PHPErrorException($errno, $errstr, $errfile, $errline);

            // output error message to STDERR if CLI
            if ( defined('STDERR') ){
                fputs(STDERR, $e);
            }

            _throw( $e );
        }
        return TRUE;    // Otherwise, ignore all errors
    }

    /**
     *    Framework global exception handler
     *
     * @param Exception $e
     */
    public static function onUnhandledException( $e )
    {
        _catch( $e );

        log_fatal( "system,error", "charcoal_global_exception_handler:" . $e->getMessage() );

        Charcoal_Framework::handleException( $e );
    }

    /*
     *    Framework global shutdown handler
     */
    public static function onShutdown()
    {
    //    log_info( "system,debug", "shutdown", 'Shutdown handler start' );

/*
        if ( $error = error_get_last() )
        {
            switch( $error['type'] )
            {
                case E_ERROR:
                case E_PARSE:
                case E_CORE_ERROR:
                case E_CORE_WARNING:
                case E_COMPILE_ERROR:
                case E_COMPILE_WARNING:
                case E_USER_ERROR:

                    $e = new Charcoal_PHPErrorException($error['type'], $error['message'], $error['file'], $error['line']);
                    Charcoal_FrameworkExceptionStack::push( $e );
                    break;
            }
        }
        */

        while( $e = Charcoal_FrameworkExceptionStack::pop() )
        {
            // Delegate framework exception handling to handlers
            Charcoal_Framework::handleException( $e );
        }
    }

    static $bootstrap_classes  = array(

                // Basic enum classes
                'Charcoal_EnumMemoryUnit'                         => 'constant',

                // Basic interface classes
                'Charcoal_IObject'                                 => 'interface',
                'Charcoal_ICollection'                             => 'interface',
                'Charcoal_IClassLoader'                         => 'interface',
                'Charcoal_IConfigProvider'                        => 'interface',
                'Charcoal_ICharcoalObject'                        => 'interface',
                'Charcoal_ICharcoalComponent'                        => 'interface',
                'Charcoal_IDebugtraceRenderer'                    => 'interface',
                'Charcoal_IExceptionHandler'                    => 'interface',
                'Charcoal_ILogger'                                => 'interface',
                'Charcoal_IRegistry'                            => 'interface',
                'Charcoal_ICodebase'                            => 'interface',
                'Charcoal_IContainer'                            => 'interface',
                'Charcoal_IUnboxable'                             => 'interface',
                'Charcoal_IEnvironment'                         => 'interface',

                // traits
                'Charcoal_ParamTrait'                             => 'trait',
                'Charcoal_ScalarTrait'                             => 'trait',
                'Charcoal_ArrayTrait'                             => 'trait',

                // Basic object classes
                'Charcoal_Object'                                 => 'class/base',
                'Charcoal_Class'                                 => 'class/base',
                'Charcoal_ObjectPath'                             => 'class/base',
                'Charcoal_CharcoalObject'                        => 'class/base',
                'Charcoal_File'                                    => 'class/base',
                'Charcoal_Interface'                             => 'class/base',
        
                // Basic exception classes
                'Charcoal_ArrayFormatException'                    => 'exception',
                'Charcoal_BooleanFormatException'                => 'exception',
                'Charcoal_BusinessException'                    => 'exception',
                'Charcoal_CharcoalException'                     => 'exception',
                'Charcoal_ClassNameEmptyException'                => 'exception',
                'Charcoal_ConfigSectionNotFoundException'        => 'exception',
                'Charcoal_ConfigException'                         => 'exception',
                'Charcoal_ClassLoaderConfigException'             => 'exception',
                'Charcoal_ClassLoaderRegistrationException'     => 'exception',
                'Charcoal_ClassNewException'                    => 'exception',
                'Charcoal_ClassNotFoundException'                 => 'exception',
                'Charcoal_CreateClassLoaderException'            => 'exception',
                'Charcoal_CreateObjectException'                => 'exception',
                'Charcoal_ExceptionHandlerException'            => 'exception',
                'Charcoal_ExceptionHandlerListInitException'     => 'exception',
                'Charcoal_HashMapFormatException'                => 'exception',
                'Charcoal_HttpStatusException'                    => 'exception',
                'Charcoal_IllegalRunModeException'                => 'exception',
                'Charcoal_InterfaceImplementException'            => 'exception',
                'Charcoal_InterfaceNotFoundException'            => 'exception',
                'Charcoal_FileNotFoundException'                 => 'exception',
                'Charcoal_FileNotReadableException'             => 'exception',
                'Charcoal_FloatFormatException'                    => 'exception',
                'Charcoal_FrameworkBootstrapException'             => 'exception',
                'Charcoal_IntegerFormatException'                => 'exception',
                'Charcoal_LogicException'                         => 'exception',
                'Charcoal_ModuleLoaderException'                => 'exception',
                'Charcoal_ParameterException'                    => 'exception',
                'Charcoal_PHPErrorException'                     => 'exception',
                'Charcoal_ProfileDirectoryNotFoundException'    => 'exception',
                'Charcoal_ProfileLoadingException'                => 'exception',
                'Charcoal_ProfileConfigFileNotFoundException'    => 'exception',
                'Charcoal_ProfileConfigFileFormatException'        => 'exception',
                'Charcoal_RegistryException'                    => 'exception',
                'Charcoal_RuntimeException'                     => 'exception',
                'Charcoal_SandboxNotLoadedException'            => 'exception',
                'Charcoal_StringFormatException'                => 'exception',
                'Charcoal_SystemEnvironmentException'            => 'exception',
                'Charcoal_UnsupportedMemoryUnitException'        => 'exception',
        
                // Primitive classes
                'Charcoal_Scalar'                         => 'class/base',
                'Charcoal_Number'                         => 'class/base',
                'Charcoal_Boolean'                        => 'class/base',
                'Charcoal_Float'                         => 'class/base',
                'Charcoal_Integer'                         => 'class/base',
                'Charcoal_String'                         => 'class/base',

                // Basic collection classes
                'Charcoal_Enum'                         => 'class/base',
                'Charcoal_Collection'                        => 'class/base',
                'Charcoal_List'                         => 'class/base',
                'Charcoal_Vector'                         => 'class/base',
                'Charcoal_HashMap'                         => 'class/base',
                'Charcoal_Queue'                         => 'class/base',
                'Charcoal_Stack'                         => 'class/base',

                // Basic config provider classes
                'Charcoal_AbstractConfigProvider'        => 'object/config_provider',
                'Charcoal_IniConfigProvider'            => 'object/config_provider',
                'Charcoal_CachedIniConfigProvider'        => 'object/config_provider',
                'Charcoal_PhpConfigProvider'            => 'object/config_provider',

                // Bootstrap classes
                'Charcoal_CharcoalComponent'                     => 'class/bootstrap',
                'Charcoal_CacheDriverList'                => 'class/bootstrap',
                'Charcoal_ClassLoader'                    => 'class/bootstrap',
                'Charcoal_ConfigPropertySet'            => 'class/bootstrap',
                'Charcoal_ConfigLoader'                 => 'class/bootstrap',
                'Charcoal_CoreHookList'                    => 'class/bootstrap',
                'Charcoal_DebugTraceRendererList'        => 'class/bootstrap',
                'Charcoal_ExceptionHandlerList'            => 'class/bootstrap',
                'Charcoal_Framework'                     => 'class/bootstrap',
                'Charcoal_FrameworkVersion'             => 'class/bootstrap',
                'Charcoal_FrameworkExceptionStack'        => 'class/bootstrap',
                'Charcoal_LoggerList'                    => 'class/bootstrap',
                'Charcoal_LogMessage'                     => 'class/bootstrap',
                'Charcoal_SandboxProfile'                 => 'class/bootstrap',
                'Charcoal_ResourceLocator'                 => 'class/bootstrap',
                'Charcoal_System'                         => 'class/bootstrap',
                'Charcoal_Sandbox'                         => 'class/bootstrap',
                'Charcoal_CoreHookEventSource'             => 'class/bootstrap',
        
                // Class loaders
                'Charcoal_FrameworkClassLoader'         => 'object/class_loader',
                'Charcoal_UserClassLoader'                => 'object/class_loader',

                // exception handler classes
                'Charcoal_AbstractExceptionHandler'                => 'object/exception_handler',
                'Charcoal_HttpErrorHeaderExceptionHandler'        => 'object/exception_handler',
                'Charcoal_HttpErrorDocumentExceptionHandler'    => 'object/exception_handler',
                'Charcoal_HtmlFileExceptionHandler'                => 'object/exception_handler',
                'Charcoal_ConsoleExceptionHandler'                => 'object/exception_handler',
                'Charcoal_StdErrorExceptionHandler'                => 'object/exception_handler',

                // debugtrace renderer classes
                'Charcoal_AbstractDebugtraceRenderer'        => 'object/debugtrace_renderer',
                'Charcoal_HtmlDebugtraceRenderer'            => 'object/debugtrace_renderer',
                'Charcoal_ConsoleDebugtraceRenderer'        => 'object/debugtrace_renderer',
                'Charcoal_LogDebugtraceRenderer'            => 'object/debugtrace_renderer',
                'Charcoal_StdErrorDebugtraceRenderer'        => 'object/debugtrace_renderer',

                // debug classes
                'Charcoal_Benchmark'                => 'class/debug',
                'Charcoal_CallHistory'                => 'class/debug',
                'Charcoal_DebugProfiler'            => 'class/debug',
                'Charcoal_MemoryBenchmark'            => 'class/debug',
                'Charcoal_MethodSpec'                => 'class/debug',
                'Charcoal_FunctionSpec'                => 'class/debug',
                'Charcoal_PhpSourceElement'            => 'class/debug',
                'Charcoal_PhpSourceInfo'            => 'class/debug',
                'Charcoal_PhpSourceParser'            => 'class/debug',
                'Charcoal_PhpSourceRenderer'        => 'class/debug',
                'Charcoal_PopupDebugWindow'            => 'class/debug',

                // logger classes
                'Charcoal_AbstractLogger'                            => 'object/logger',
                'Charcoal_CsvFileLogger'                        => 'object/logger',
                'Charcoal_FileLogger'                            => 'object/logger',
                'Charcoal_HtmlFileLogger'                        => 'object/logger',
                'Charcoal_ScreenLogger'                            => 'object/logger',
                'Charcoal_PopupScreenLogger'                    => 'object/logger',
                'Charcoal_ConsoleLogger'                        => 'object/logger',

                // registry classes
                'Charcoal_AbstractRegistry'                        => 'class/bootstrap/registry',
                'Charcoal_FileSystemRegistry'                    => 'class/bootstrap/registry',
                'Charcoal_MemoryRegistry'                        => 'class/bootstrap/registry',

                // codebase classes
                'Charcoal_AbstractCodebase'                        => 'class/bootstrap/codebase',
                'Charcoal_PlainCodebase'                        => 'class/bootstrap/codebase',

                // container classes
                'Charcoal_AbstractContainer'                     => 'class/bootstrap/container',
                'Charcoal_DIContainer'                             => 'class/bootstrap/container',

                // environment classes
                'Charcoal_AbstractEnvironment'                     => 'class/bootstrap/environment',
                'Charcoal_ArrayEnvironment'                     => 'class/bootstrap/environment',
                'Charcoal_HttpEnvironment'                         => 'class/bootstrap/environment',
                'Charcoal_ShellEnvironment'                     => 'class/bootstrap/environment',

                // utility classes
                'Charcoal_EncodingConverter'                    => 'class/util',
                'Charcoal_MemoryUtil'                            => 'class/util',

            );

    /**
     *    autoload function for bootstrap
     *
     * @param Charcoal_String|string $class_name
     *
     * @return boolean             TRUE if the class is loaded, otherwise FALSE
     */
    public static function loadClass( $class_name )
    {
        if ( !isset(self::$bootstrap_classes[$class_name]) ){
            if ( self::$debug )    echo "Class NOT found in bootstrap class loader: $class_name" . eol();
            return FALSE;
        }

        $file_name = $class_name . '.class.php';
        $pos = strpos( $file_name, 'Charcoal_' );
        if ( $pos !== FALSE ){
            $file_name = substr( $file_name, $pos + 9 /*= strlen('Charcoal_') */ );
        }

        $class_path = CHARCOAL_HOME . '/src/' . self::$bootstrap_classes[ $class_name ] . '/' . $file_name;

        if ( self::$debug )    echo "loading file[$class_path] for class: $class_name" . eol();

        /** @noinspection PhpIncludeInspection */
        include( $class_path );

        if ( self::$debug )    echo "Class found in bootstrap class loader: $class_name" . eol();

        return TRUE;
    }

    /**
     *    run bootstrap
     *
     * @param boolean $debug
     */
    public static function run( $debug = FALSE )
    {
        self::$debug = $debug;

        // register bootstrap clas loader
        if ( !spl_autoload_register('Charcoal_Bootstrap::loadClass',false) )
        {
            echo "registering bootstrap class loader failed." . eol();
            exit;
        }

        // register system handlers
        register_shutdown_function( 'Charcoal_Bootstrap::onShutdown' );
        set_error_handler( "Charcoal_Bootstrap::onUnhandledError" );
        set_exception_handler( "Charcoal_Bootstrap::onUnhandledException" );
    }

}


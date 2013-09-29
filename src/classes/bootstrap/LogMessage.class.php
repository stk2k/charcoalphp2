<?php
/**
* log message object
*
* PHP version 5
*
* @package    classes.bootstrap
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_LogMessage extends Charcoal_Object
{
	private $level;
	private $tag;
	private $message;
	private $file;
	private $line;
	private $logger_names;

	/**
	 *  Constructor
	 * 
	 * @param Charcoal_String $level           log level
	 * @param Charcoal_String $message         log message
	 * @param Charcoal_String $tag             log tag
	 * @param Charcoal_String $file            file path
	 * @param Charcoal_Integer $line           line of file
	 * @param Charcoal_Vector $logger_names    target loggers
	 */
	public function __construct( $level, $message, $tag, $file, $line, $logger_names )
	{
/*
//		Charcoal_ParamTrait::checkString( 1, $level );
//		Charcoal_ParamTrait::checkString( 2, $message );
//		Charcoal_ParamTrait::checkString( 3, $tag );
//		Charcoal_ParamTrait::checkString( 4, $file );
//		Charcoal_ParamTrait::checkInteger( 5, $line );
//		Charcoal_ParamTrait::checkVector( 6, $logger_names );
*/

		$this->level         = $level;
		$this->message       = $message;
		$this->tag           = $tag;
		$this->file          = $file;
		$this->line          = $line;
		$this->logger_names  = v($logger_names);
	}

	/**
	 *	get level
	 */
	public function getLevel()
	{
		return $this->level;
	}

	/**
	 *	get tag
	 */
	public function getTag()
	{
		return $this->tag;
	}

	/**
	 *	get message
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 *	get file
	 */
	public function getFile()
	{
		return $this->file;
	}

	/**
	 *	get line
	 */
	public function getLine()
	{
		return $this->line;
	}

	/**
	 *	get logger names
	 */
	public function getLoggerNames()
	{
		return $this->logger_names;
	}

	/*
	 *  String expression of this object
	 *
	 * @return string
	 */
	public function toString()
	{
		return "[$this->logger_names][{$this->tag}][{$this->level}]{$this->message}@{$this->file}({$this->line})";
	}
}



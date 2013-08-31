<?php
/**
* log message object
*
* PHP version 5
*
* @package    core
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

	public function __construct( 
						Charcoal_String $level, 
						Charcoal_String $tag, 
						Charcoal_String $message, 
						Charcoal_String $file, 
						Charcoal_Integer $line, 
						Charcoal_Vector $logger_names
					)
	{
		$this->level         = $level;
		$this->tag           = $tag;
		$this->message       = $message;
		$this->file          = $file;
		$this->line          = $line;
		$this->logger_names  = $logger_names;
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
		return "[{$this->level}]{$this->message}@{$this->file}({$this->line})";
	}
}



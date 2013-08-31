<?php
/**
* XML要素
*
* PHP version 5
*
* @package    exceptions
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_XmlElement implements Iterator, Countable
{
	private $tag;
	private $children;
	private $contents;
	private $attributes;

	/**
	 *	Contructor
	 */
	public function __construct( Charcoal_String $tag, Charcoal_Vector $children = NULL, Charcoal_String $contents = NULL, Charcoal_Vector $attributes = NULL )
	{
		$this->tag = $tag;
		$this->children = $children;
		$this->contents = $contents;
		$this->attributes = $attributes;
	}

	/**
	 *	Get child by tag name
	 */
/*
	public function __call($name, $arguments)
	{
		return $this->__get($name);
	}
*/

	/**
	 *	Get child by tag name
	 */
	public function __get( $name )
	{
print "name:$name<br>";

		if ( $this->children && is_array($this->children) ){
			foreach( $this->children as $child ){
				$tag = $child->getTag();
				if ( strcmp($tag,$name) === 0 ){
					return $child;
				}
			}
		}
		return NULL;
	}

	/**
	 *	Get Tag
	 */
	public function getTag()
	{
		return $this->tag;
	}

	/**
	 *	Set Tag
	 */
	public function setTag( Charcoal_String $tag )
	{
		$this->tag = $tag;
	}

	/**
	 *	Get Children
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
	 *	Set Children
	 */
	public function setChildren( Charcoal_Vector $children )
	{
		$this->children = uv($children);
	}

	/**
	 *	Get content
	 */
	public function getContents()
	{
		return $this->contents;
	}

	/**
	 *	Set content
	 */
	public function setContents( Charcoal_String $contents )
	{
		$this->contents = us($contents);
	}

	/**
	 *	Add content
	 */
	public function addContents( Charcoal_String $contents )
	{
		$this->contents .= us($contents);
	}

	/**
	 *	Get Attributes
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 *	Set Attributes
	 */
	public function setAttributes( Charcoal_Vector $attributes)
	{
		$this->attributes = $attributes;
	}

	/**
	 *	Add Child
	 */
	public function add( Charcoal_XmlElement $child )
	{
		$this->children[] = $child;
	}

	/*
	 *	get child by offset
	 */
	public function offsetGet($offset)
	{
		return isset($this->children[$offset]) ? $this->children[$offset] : NULL;
	}

	/*
	 *	set child by offset
	 */
	public function offsetSet($offset, $value)
	{
		$this->children[$offset] = $value;
	}

	/*
	 *	get offset of a child
	 */
	public function offsetExists($offset)
	{
		return FALSE !== array_search( $offset, $this->children );
	}

	/*
	 * Coutable interface implement : count
	 */
	public function count()
	{
		return count($this->children);
	}

	/*
	 *	Iterator interface implement : rewind
	 */
	public function rewind()
	{
		reset($this->children);
	}

	/*
	 *	Iterator interface implement : current
	 */
	public function current()
	{
		$offset = current($this->children);
		return $this->offsetGet($offset);
	}

	/*
	 *	Iterator interface implement : key
	 */
	public function key()
	{
		return key($this->children);
	}

	/*
	 *	Iterator interface implement : next
	 */
	public function next()
	{
		return next($this->children);
	}

	/*
	 *	Iterator interface implement : valid
	 */
	public function valid()
	{
		$offset = current($this->children);
		return $this->offsetExists($offset);
	}


}


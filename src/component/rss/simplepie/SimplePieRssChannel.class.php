<?php
/**
* SimplePie RSS Channel
*
* PHP version 5
*
* @package    component.rss.simplepie
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_SimplePieRSSChannel extends Charcoal_Object
{
	private $subscribe_url;
	private $link;
	private $title;
	private $description;
	private $items;

	/*
	 *  Constructor
	 *
	 * @param array $data
	 */
	public function __construct( array $data, array $items )
	{
		parent::__construct();

		$this->subscribe_url = isset($data['subscribe_url']) ? $data['subscribe_url'] : '';
		$this->link = isset($data['link']) ? $data['link'] : '';
		$this->title = isset($data['title']) ? $data['title'] : '';
		$this->description = isset($data['description']) ? $data['description'] : '';

		$this->items = array();
		foreach ($items as $item)
		{
			$this->items[] = new Charcoal_SimplePieRSSItem( $item );
		}
	}

	/**
	 * Returns subscribe URL
	 *
	 * @return string 
	 */
	public function getSubscribeURL()
	{
		return $this->subscribe_url;
	}

	/**
	 * Returns link
	 *
	 * @return string 
	 */
	public function getLink()
	{
		return $this->link;
	}

	/**
	 * Returns title
	 *
	 * @return string 
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Returns description
	 *
	 * @return string 
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Returns items
	 *
	 * @return array 
	 */
	public function getItems()
	{
		return $this->items;
	}

}


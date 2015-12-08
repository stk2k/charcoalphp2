<?php
/**
* SimplePie RSS Item
*
* PHP version 5
*
* @package    component.rss.simplepie
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_SimplePieRSSItem extends Charcoal_Object
{
    private $link;
    private $title;
    private $description;
    private $date;

    /**
     *  Constructor
     *
     * @param array $data
     */
    public function __construct( array $data )
    {
        parent::__construct();

        $this->link = isset($data['link']) ? $data['link'] : '';
        $this->title = isset($data['title']) ? $data['title'] : '';
        $this->description = isset($data['description']) ? $data['description'] : '';
        $this->date = isset($data['date']) ? $data['date'] : '';
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
     * Returns date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

}


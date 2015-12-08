<?php
/**
* RSS Item
*
* PHP version 5
*
* @package    component.rss.simplepie
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_FeedCreatorFeedItem extends Charcoal_Object
{
    private $component;

    private $item;

    /**
     *  Constructor
     *
     * @param array $data
     */
    public function __construct( Charcoal_FeedCreatorComponent $component, array $data )
    {
        parent::__construct();

        $this->component = $component;

        $this->item = new FeedItem();

        $this->item->link = isset($data['link']) ? $data['link'] : '';
        $this->item->title = isset($data['title']) ? $data['title'] : '';
        $this->item->description = isset($data['description']) ? $data['description'] : '';
        $this->item->date = isset($data['date']) ? $data['date'] : '';
        $this->item->author = isset($data['author']) ? $data['author'] : '';
    }

    /**
     * get raw item
     *
     * @param FeedItem
     */
    public function getRawItem()
    {
        return $this->item;
    }

    /**
     * generate feed and save to file
     *
     * @param Charcoal_String|string $filename       file name for the feed
     * @param Charcoal_String|string $format         format name of the feed
     */
    public function saveFeed( $filename, $format = Charcoal_FeedCreatorComponent::DEFAULT_FORMAT )
    {
        $this->component->saveFeed( $filename, $format );
    }

    /**
     * generate feed and output to the buffer
     *
     * @param Charcoal_String|string $format         format name of the feed
     */
    public function outputFeed( $format = Charcoal_FeedCreatorComponent::DEFAULT_FORMAT )
    {
        $this->component->outputFeed( $format );
    }

    /**
     * Returns link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->item->link;
    }

    /**
     * set link
     *
     * @param Charcoal_String|string $link
     */
    public function setLink( $link )
    {
        Charcoal_ParamTrait::validateString( 1, $link, TRUE );

        $this->item->link = us($link);

        return $this;
    }

    /**
     * Returns title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->item->title;
    }

    /**
     * set title
     *
     * @param Charcoal_String|string $title
     */
    public function setTitle( $title )
    {
        Charcoal_ParamTrait::validateString( 1, $title, TRUE );

        $this->item->title = us($title);

        return $this;
    }

    /**
     * Returns description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->item->description;
    }

    /**
     * set description
     *
     * @param Charcoal_String|string $description
     */
    public function setDescription( $description )
    {
        Charcoal_ParamTrait::validateString( 1, $description, TRUE );

        $this->item->description = us($description);

        return $this;
    }

    /**
     * Returns date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->item->date;
    }

    /**
     * set date
     *
     * @param Charcoal_String|string $date
     */
    public function setDate( $date )
    {
        Charcoal_ParamTrait::validateString( 1, $date, TRUE );

        $this->item->date = us($date);

        return $this;
    }

    /**
     * Returns author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->item->author;
    }

    /**
     * set author
     *
     * @param Charcoal_String|string $author
     */
    public function setAuthor( $author )
    {
        Charcoal_ParamTrait::validateString( 1, $author, TRUE );

        $this->item->author = us($author);

        return $this;
    }

}


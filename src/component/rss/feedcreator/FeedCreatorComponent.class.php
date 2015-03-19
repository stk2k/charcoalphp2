<?php
/**
* FeedCreator RSS generator Component
*
* PHP version 5
*
* @package    component.rss.simplepie
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
require_once( 'FeedCreatorFeedItem.class.php' );
require_once( 'FeedCreatorComponentException.class.php' );

require_once( 'feedcreator/feedcreator.class.php' );

class Charcoal_FeedCreatorComponent extends Charcoal_CharcoalComponent implements Charcoal_IComponent
{
	const DEFAULT_FORMAT = 'RSS1.0';

	private $creator;

	/**
	 *  Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->creator = new UniversalFeedCreator();
	}

	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );
	}

	/**
	 * add feed item
	 *
	 * @return Charcoal_FeedCreatorFeedItem       new feed item
	 */
	public function addItem( $data = array() )
	{
		Charcoal_ParamTrait::validateHashMap( 1, $data );

		$item = new Charcoal_FeedCreatorFeedItem( $this, $data );

		$this->creator->addItem( $item->getRawItem() );

		return $item;
	}

	/**
	 * set title
	 *
	 * @param Charcoal_String|string $title       title of the feed
	 */
	public function setTitle( $title )
	{
		Charcoal_ParamTrait::validateString( 1, $title, TRUE );

		$this->creator->title = us($title);

		return $this;
	}

	/**
	 * set link
	 *
	 * @param Charcoal_String|string $link       link of the feed
	 */
	public function setLink( $link )
	{
		Charcoal_ParamTrait::validateString( 1, $link, TRUE );

		$this->creator->link = us($link);

		return $this;
	}

	/**
	 * set description
	 *
	 * @param Charcoal_String|string $description       description of the feed
	 */
	public function setDescription( $description )
	{
		Charcoal_ParamTrait::validateString( 1, $description, TRUE );

		$this->creator->description = us($description);

		return $this;
	}

	/**
	 * set syndication URL
	 *
	 * @param Charcoal_String|string $syndicationURL       syndication URL of the feed
	 */
	public function setSyndicationURL( $syndicationURL )
	{
		Charcoal_ParamTrait::validateString( 1, $syndicationURL, TRUE );

		$this->creator->syndicationURL = us($syndicationURL);

		return $this;
	}

	/**
	 * generate feed and save to file
	 * 
	 * @param Charcoal_String|string $filename       file name for the feed
	 * @param Charcoal_String|string $format         format name of the feed
	 */
	public function saveFeed( $filename, $format = self::DEFAULT_FORMAT )
	{
		Charcoal_ParamTrait::validateString( 1, $filename );
		Charcoal_ParamTrait::validateString( 2, $format );

		$this->creator->saveFeed( us($format), us($filename), false );
	}

	/**
	 * generate feed and output to the buffer
	 * 
	 * @param Charcoal_String|string $format         format name of the feed
	 */
	public function outputFeed( $format = self::DEFAULT_FORMAT )
	{
		Charcoal_ParamTrait::validateString( 1, $format );

		echo 'outputFeed' . PHP_EOL;

		$this->creator->outputFeed( us($format) );
	}

}


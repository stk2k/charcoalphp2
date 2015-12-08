<?php
/**
* SimplePie RSS Reader Component
*
* PHP version 5
*
* @package    component.rss.simplepie
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/
require_once( 'SimplePieRssItem.class.php' );
require_once( 'SimplePieRssChannel.class.php' );
require_once( 'SimplePieComponentException.class.php' );

require_once( 'simplepie/autoloader.php' );

class Charcoal_SimplePieComponent extends Charcoal_CharcoalComponent implements Charcoal_IComponent
{
    private $simple_pie;

    /**
     *  Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->simple_pie = new SimplePie();
    }

    /**
     * Initialize instance
     *
     * @param Charcoal_Config $config   configuration data
     */
    public function configure( $config )
    {
        parent::configure( $config );

        $enable_cahche  = $config->getBoolean( 'enable_cahche', true );
        $cache_dir      = $config->getString( 'cache_dir', CHARCOAL_CACHE_DIR . '/simplepie', TRUE );
        $duration       = $config->getInteger( 'duration', 1800 );

        $this->simple_pie->enable_cache( ub($enable_cahche) );
        $this->simple_pie->set_cache_location( us($cache_dir) );
        $this->simple_pie->set_cache_duration( ui($duration) );
    }

    /**
     * get raw RSS data
     *
     * @return string    RSS data
     */
    public function getRawData()
    {
        return $this->simple_pie->get_raw_data();
    }

    /**
     * retrieve rss
     *
     * @param string $url
     */
    public function getFeed( $url, $date_format = NULL )
    {
        $feed = $this->simple_pie;

        $feed->set_feed_url( $url );
        $feed->init();
        $feed->handle_content_type();

        if ( $this->simple_pie->error() )
        {
            $message = $feed->error();
            _throw( new SimplePieComponentException( $message ) );
        }

        $items = array();
        foreach ($feed->get_items() as $item)
        {
            $desc_tags = ($item->get_item_tags('', 'description'));
            $description = $desc_tags ? $desc_tags[0]['data'] : '';
            $description = strip_tags($description);
            $items[] = array(
                    'date' =>  $item->get_date($date_format),
                    'link' =>  $item->get_link(),
                    'title' =>  $item->get_title(),
                    'description' => $description, //$item->get_description(),
                    'date' =>  $item->get_date(),
                );
        }

        $rss_channel = array(
                'subscribe_url' => $feed->subscribe_url(),
                'link' => $feed->get_link(),
                'description' => $feed->get_description(),
                'title' => $feed->get_title(),
                'items' => $items,
            );

        return new Charcoal_SimplePieRSSChannel( $rss_channel, $items );
    }

}


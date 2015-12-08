<?php
/**
* PEAR::Pager Component
*
* PHP version 5
*
* @package    component.rss.simplepie
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

require_once( 'Pager/Pager.php' );

class Charcoal_PearPagerComponent extends Charcoal_CharcoalComponent implements Charcoal_IComponent
{
    private $pager;

    /**
     *  Constructor
     */
    public function __construct()
    {
        parent::__construct();
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
     * create
     *
     */
    public function create( $params )
    {
        Charcoal_ParamTrait::validateHashMap( 1, $params );

        $this->pager = @Pager::factory( $params );
    }

    /**
     * get links
     *
     */
    public function getLinks()
    {
        return $this->pager->getLinks();
    }

    /**
     * get current page id
     *
     */
    public function getCurrentPageID()
    {
        return $this->pager->getCurrentPageID();
    }

    /**
     * get page data
     *
     */
    public function getPageData()
    {
        return $this->pager->getPageData();
    }

    /**
     * get number of items
     *
     */
    public function numItems()
    {
        return $this->pager->numItems();
    }


}


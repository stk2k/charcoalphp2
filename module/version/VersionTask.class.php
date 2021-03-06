<?php
/**
* display version command
*
* PHP version 5
*
* @package    renderers
* @author     stk2k <stk2k@sazysoft.com>
* @copyright  2008 stk2k, sazysoft
*/

class VersionTask extends Charcoal_Task
{
    /**
     * Process events
     *
     * @param Charcoal_IEventContext $context   event context
     *
     * @return boolean
     */
    public function processEvent( $context )
    {
        $version = Charcoal_Framework::getVersion()->toString();
        $this_year = date('Y');

        echo "CharcoalPHP {$version}." . PHP_EOL;
        echo "Copyright (c)2008-$this_year CharcoalPHP team." . PHP_EOL;

        return b(true);
    }
}

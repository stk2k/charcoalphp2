<?php
/**
* Setup event
*
* PHP version 5
*
* @package    events
* @author     CharcoalPHP Development Team
* @copyright  2008 - 2013 CharcoalPHP Development Team
*/

class Charcoal_SetupEvent extends Charcoal_SystemEvent implements Charcoal_IEvent
{
	/**
	 * Initialize instance
	 *
	 * @param Charcoal_Config $config   configuration data
	 */
	public function configure( $config )
	{
		parent::configure( $config );

		$config->set( s('priority'), Charcoal_EnumEventPriority::SYSTEM );

		parent::configure( $config );
	}
}


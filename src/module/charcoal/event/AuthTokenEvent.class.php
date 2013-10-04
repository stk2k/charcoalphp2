<?php
/**
* Setup event
*
* PHP version 5
*
* @package    modules.charcoal.event
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_AuthTokenEvent extends Charcoal_SystemEvent implements Charcoal_IEvent
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


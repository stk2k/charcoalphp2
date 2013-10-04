<?php
/**
* environment implemented by shell variables
*
* PHP version 5
*
* @package    class.bootstrap.environment
* @author     CharcoalPHP Development Team
* @copyright  2008 stk2k, sazysoft
*/

class Charcoal_ShellEnvironment extends Charcoal_AbstractEnvironment
{
	/**
	 *  Constructor
	 */
	public function __construct()
	{
		$now_time = time();

		$values = array(
				'%Y4%' => date("Y",$now_time),
				'%Y2%' => date("y",$now_time),
				'%M2%' => date("m",$now_time),
				'%M1%' => date("n",$now_time),
				'%D2%' => date("d",$now_time),
				'%D1%' => date("j",$now_time),
				'%H2%' => date("H",$now_time),
				'%H1%' => date("G",$now_time),
				'%h2%' => date("h",$now_time),
				'%h1%' => date("g",$now_time),
				'%M%'  => date("i",$now_time),
				'%S%'  => date("s",$now_time),
				'%REMOTE_ADDR%' => '',
				'%REQUEST_ID%' => Charcoal_System::hash(),
				'%REQUEST_PATH%' => '',
				'%REQUEST_TIME%' => date('Y-m-d H:i:s',$now_time),
			);

		parent::__construct( $values );
	}

}


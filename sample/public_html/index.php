<?php

//==================================================================
// configure framework
//

// error_reporting
error_reporting( E_ALL & ~E_STRICT );

// Run Mode
define( 'CHARCOAL_RUNMODE', 'http' );

// Retrieve Host Name
$host = php_uname('n');

// Setup Framework Basic Constants
switch($host)
{
case "localhost":
default:
	define( 'CHARCOAL_PROFILE', 'localhost' );
	define( 'CHARCOAL_BASE_DIR',  dirname(dirname(dirname(dirname(__FILE__)))) );
	define( 'CHARCOAL_HOME', CHARCOAL_BASE_DIR . '/charcoal' );
	define( 'CHARCOAL_WEBAPP_DIR', CHARCOAL_BASE_DIR . "/charcoal/sample/web_app" );
	break;
}

define( 'CHARCOAL_PROJECT', "charcoal" );
define( 'CHARCOAL_APPLICATION', "frontend_pc" );
define( 'CHARCOAL_DEFAULT_PROCPATH', "@:hello" );
define( 'CHARCOAL_DEFAULT_TIMEZONE', "Asia/Tokyo" );

//==================================================================
// include framework globals
//
include( CHARCOAL_HOME . '/charcoal.inc.php');

//==================================================================
// run framework
//

Charcoal_Bootstrap::run();
Charcoal_Framework::run();

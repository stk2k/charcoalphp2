<?php

//==================================================================
// configure framework
//

// error_reporting
error_reporting( E_ALL & ~E_STRICT );

// Run Mode
define( 'CHARCOAL_RUNMODE', 'shell' );

// Setup Framework Basic Constants
define( 'CHARCOAL_BASE_DIR',  dirname(dirname(__FILE__)) );
define( 'CHARCOAL_HOME', CHARCOAL_BASE_DIR );
define( 'CHARCOAL_WEBAPP_DIR', CHARCOAL_HOME . '/web_app' );
define( 'CHARCOAL_PROJECT', 'mysql' );
define( 'CHARCOAL_APPLICATION', 'mysql' );
define( 'CHARCOAL_DEFAULT_TIMEZONE', 'Asia/Tokyo' );
define( 'CHARCOAL_PROFILE', 'localhost' );
define( 'CHARCOAL_DEBUG_OUTPUT', 'shell' );
define( 'CHARCOAL_CACHE_DIR', CHARCOAL_HOME . "/cache/" . CHARCOAL_APPLICATION );

//==================================================================
// include framework globals
//
include( CHARCOAL_HOME . '/charcoal.inc.php');

//==================================================================
// run framework
//

Charcoal_Bootstrap::run();
Charcoal_Framework::run();

<?php

//==================================================================
// configure framework
//

// error_reporting
error_reporting( E_ALL & ~E_STRICT );

// Run Mode
define( 'CHARCOAL_RUNMODE', 'shell' );

// Setup Framework Basic Constants
define( 'CHARCOAL_BASE_DIR',  dirname(dirname(dirname(__FILE__))) );
define( 'CHARCOAL_HOME', CHARCOAL_BASE_DIR . '/charcoal' );
define( 'CHARCOAL_WEBAPP_DIR', CHARCOAL_HOME . "/test/web_app" );
define( 'CHARCOAL_PROJECT', "test" );
define( 'CHARCOAL_APPLICATION', "test" );
define( 'CHARCOAL_DEFAULT_PROCPATH', "index" );
define( 'CHARCOAL_DEFAULT_TIMEZONE', "Asia/Tokyo" );
define( 'CHARCOAL_PROFILE', "shell" );

//==================================================================
// include framework globals
//
include( CHARCOAL_HOME . '/charcoal.inc.php');

//==================================================================
// run framework
//

Charcoal_Bootstrap::run();
Charcoal_Framework::run();


<?php

// Application base path
define('APPPATH', __DIR__);

// Include necessary path for class loading
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPPATH . '/lib'),
    realpath(APPPATH . '/lib/Sparrow'),
    realpath(APPPATH . '/includes'),
    get_include_path(),
)));


//loading the constants file
require_once 'config/constants.php';


// Load app engine
include_once 'App.php';

// Initiate engine and run!
App::init(APPPATH . '/config/app.ini');
App::run();
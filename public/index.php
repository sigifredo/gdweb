<?php

// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

defined('GD3W_PATH')
|| define('GD3W_PATH', "/tmp/gdweb/www");

defined('GDPG_PATH')
|| define('GDPG_PATH', "/tmp/gdweb/pg");

// Define application environment
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
                             realpath(APPLICATION_PATH . '/../library'),
                             realpath(APPLICATION_PATH . '/forms'),
                             realpath(APPLICATION_PATH . '/models'),
                             get_include_path(),
                         )));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
->run();

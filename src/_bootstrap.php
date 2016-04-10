<?php

/**
 * Bootstraps the application
 */

// Composer
require '../vendor/autoload.php';

// Add headers to response object
Flight::request()->headers = getallheaders();
unset(Flight::request()->headers['User-Agent']);

// Variables
Flight::set('version', '0.0.1');
Flight::set('dbs', dirname(__DIR__) . '/databases/');

// HTTP routes
require '../src/_routes.php';

// Let's go!
Flight::start();
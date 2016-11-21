<?php

// Composer
require __DIR__ . '/../vendor/autoload.php';

// Path to databases
$db_dir = realpath(__DIR__ . '/../databases/');

// Routing
$klein = new \Klein\Klein();

// Request Hash
// $klein->respond('GET',  '/-/-/hash', function ($request, $response) {
// 	$hash = hash('sha256', $_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['REQUEST_URI']);
// 	return $hash;
// });

// Root
$klein->respond('HEAD', '/', ['Server', 'welcome']);
$klein->respond('GET',  '/', ['Server', 'list_databases']);
// $klein->respond('POST', '/', ['Server', 'query']);

// Databases
$klein->respond('HEAD', '/[:database]', ['Server', 'database_exists']);
$klein->respond('GET',  '/[:database]', ['Server', 'list_tables']);

// Tables
$klein->respond('HEAD', '/[:database]/[:table]', ['Server', 'table_exists']);
$klein->respond('GET',  '/[:database]/[:table]', ['Server', 'select_all']);
// $klein->respond('POST', '/[:database]/[:table]', ['Server', 'insert_record']);

// Data
// $klein->respond('HEAD',   '/[:database]/[:table]/[:id]', ['Server', 'record_exists']);
// $klein->respond('GET',    '/[:database]/[:table]/[:id]', ['Server', 'select_all_id']);
// $klein->respond('POST',   '/[:database]/[:table]/[:id]', ['Server', 'insert_record_id']);
// $klein->respond('PUT',    '/[:database]/[:table]/[:id]', ['Server', 'update_record']);
// $klein->respond('DELETE', '/[:database]/[:table]/[:id]', ['Server', 'delete_record']);

// Queries
// $klein->respond('GET',    '/[:database]/[:table]/[:column]/[:value]', ['Server', 'select_where']);
// $klein->respond('GET',    '/[:database]/[:table]/query',         ['Server', 'select_wheres']);

$klein->dispatch();

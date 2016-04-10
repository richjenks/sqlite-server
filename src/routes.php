<?php

/**
 * HTTP routes
 */

// Welcome
Flight::route('HEAD /',   ['SQLiteServer\Server', 'welcome']);

// Databases
Flight::route('GET /',             ['SQLiteServer\Server', 'list_databases']);
Flight::route('HEAD /@database',   ['SQLiteServer\Server', 'database_exists']);
Flight::route('POST /@database',   ['SQLiteServer\Server', 'create_database']);
Flight::route('DELETE /@database', ['SQLiteServer\Server', 'delete_database']);
Flight::route('PUT /@database',    ['SQLiteServer\Server', 'rename_database']);

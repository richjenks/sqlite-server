<?php

/**
 * HTTP routes
 */

// Welcome
Flight::route('HEAD /', ['SQLiteServer\Server', 'welcome']);
Flight::route('GET /',  ['SQLiteServer\Server', 'list_databases']);

// Databases
Flight::route('HEAD /@database',   ['SQLiteServer\Server', 'database_exists']);
Flight::route('GET /@database',    ['SQLiteServer\Server', 'list_tables']);
Flight::route('POST /@database',   ['SQLiteServer\Server', 'create_database']);
Flight::route('PUT /@database',    ['SQLiteServer\Server', 'rename_database']);
Flight::route('DELETE /@database', ['SQLiteServer\Server', 'delete_database']);

// Tables
// Flight::route('HEAD /@database/@table',   ['SQLiteServer\Server', 'table_exists']); // [ ]
// Flight::route('GET /@database/@table',    ['SQLiteServer\Server', 'select_all']);   // [ ]
// Flight::route('POST /@database/@table',   ['SQLiteServer\Server', 'create_table']); // [ ]
// Flight::route('PUT /@database/@table',    ['SQLiteServer\Server', 'rename_table']); // [ ]
// Flight::route('DELETE /@database/@table', ['SQLiteServer\Server', 'delete_table']); // [ ]

// Data
// Flight::route('HEAD /@database/@table(/@id)',   ['SQLiteServer\Server', 'select']); // [ ]
// Flight::route('GET /@database/@table(/@id)',    ['SQLiteServer\Server', 'select']); // [ ]
// Flight::route('POST /@database/@table',         ['SQLiteServer\Server', 'insert']); // [ ]
// Flight::route('PUT /@database/@table',          ['SQLiteServer\Server', 'update']); // [ ]
// Flight::route('DELETE /@database/@table',       ['SQLiteServer\Server', 'delete']); // [ ]
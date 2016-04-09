<?php namespace SQLiteServer;

/**
 * Handles server- and database-level operations
 * but not table- or data-level operations
 */
class Server {

	public static function welcome() { header('Welcome: SQLite Server v' . \Flight::get('version')); }

	public static function database_exists($name) {
		$db = new Database;
		if (!file_exists($db->database_path($name))) \Flight::halt(404);
	}

	public static function list_databases() {
		$db = new Database;
		$files = scandir(\Flight::get('dbs'));

		// Remove non-database files
		foreach ($files as $key => $file)
			if (!$db->validate_database($file) || substr($file, -3) !== '.db')
				unset($files[$key]);

		// Remove extension
		foreach ($files as $key => $file) $files[$key] = substr($file, 0, -3);

		// Return readable list
		sort($files, SORT_NATURAL | SORT_FLAG_CASE);
		\Flight::json($files);
	}

	public static function create_database($name) {
		$db = new Database;

		if (!$db->validate_database($name)) \Flight::halt(409, 'Invalid database name');
		if (file_exists($db->database_path($name))) \Flight::halt(409, 'Database already exists');

		$db->create_database($name);
		\Flight::halt(201);
	}

	public static function delete_database($name) {
		$db = new Database;
		if (!file_exists($db->database_path($name))) \Flight::halt(404, 'No such database');
		$db->drop_database($name);
	}

	public static function rename_database($name) {
		$db = new Database;
		$new = \Flight::request()->getBody();

		if (!file_exists($db->database_path($name))) \Flight::halt(404, 'No such database');
		if (!$db->validate_database($new)) \Flight::halt(409, 'Invalid database name');

		$db->rename_database($name, $new);
	}

}
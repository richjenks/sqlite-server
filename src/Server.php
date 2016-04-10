<?php namespace SQLiteServer;

/**
 * Handles server- and database-level operations
 * but not table- or data-level operations
 */
class Server {

	/**
	 * Welcome message
	 */
	public static function welcome() { header('Welcome: SQLite Server v' . \Flight::get('version')); }

	/**
	 * Checks whether the given database exists
	 *
	 * @param string $name Database name
	 * @return bool Whether the database exists
	 */
	public static function database_exists($name) {
		$db = new Database;
		if (!file_exists($db->database_path($name))) \Flight::halt(404);
	}

	/**
	 * Gets all databases
	 *
	 * @return array List of database names, minus extensions
	 */
	public static function list_databases() {
		$db        = new Database;
		$databases = $db->get_databases(\Flight::get('dbs'));
		\Flight::json($databases);
	}

	/**
	 * Creates a new database
	 *
	 * @param string $name Database name
	 */
	public static function create_database($name) {
		$db = new Database;

		if (!$db->validate_database($name)) \Flight::halt(409, 'Invalid database name');
		if (file_exists($db->database_path($name))) \Flight::halt(409, 'Database already exists');

		$db->create_database($name);
		\Flight::halt(201);
	}

	/**
	 * Drops a database, turning a DELETE method to a DROP SQL equivalent
	 *
	 * @param string $name Database name
	 */
	public static function delete_database($name) {
		$db = new Database;
		if (!file_exists($db->database_path($name))) \Flight::halt(404, 'No such database');
		$db->drop_database($name);
	}

	/**
	 * Moves a database from one name to another
	 * Has only one parameter from the URL because new name comes from request body
	 *
	 * @param string $name Current database name
	 */
	public static function rename_database($name) {
		$db  = new Database;
		$new = \Flight::request()->getBody();

		if (!file_exists($db->database_path($name))) \Flight::halt(404, 'No such database');
		if (!$db->validate_database($new)) \Flight::halt(409, 'Invalid database name');

		$db->rename_database($name, $new);
	}

}
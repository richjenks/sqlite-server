<?php namespace SQLiteServer;

/**
 * Front controller/dispatcher
 */
class Server {

	/**
	 * Welcome message
	 */
	public static function welcome() { header('Welcome: SQLite Server v' . \Flight::get('version')); }

	/**
	 * Checks whether given database exists
	 *
	 * @param string $database Database name
	 */
	public static function database_exists($database) {
		$db = new Database;
		if ($db->database_exists($database)) {
			\Flight::halt(200, 'Database exists');
		} else {
			\Flight::halt(404, 'Database doesn\'t exist');
		}
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
		if ($db->database_exists($name)) \Flight::halt(409, 'Database already exists');

		$db->create_database($name);
		\Flight::halt(201, "Database '$name' created");
	}

	/**
	 * Drops a database, turning a DELETE method to a DROP SQL equivalent
	 *
	 * @param string $name Database name
	 */
	public static function delete_database($name) {
		$db = new Database;
		if (!$db->database_exists($name)) \Flight::halt(404, 'No such database');
		$db->drop_database($name);
		\Flight::halt(200, "Database '$name' deleted");
	}

	/**
	 * Moves a database from one name to another
	 * Second parameter only exists for testing
	 *
	 * @param string $name Current database name
	 */
	public static function rename_database($name, $new = false) {
		$db = new Database;
		if (!$new) $new = \Flight::request()->getBody();

		if (!$db->database_exists($name)) \Flight::halt(404, 'No such database');
		if (!$db->validate_database($new)) \Flight::halt(409, 'Invalid database name');

		$db->rename_database($name, $new);
		\Flight::halt(200, "Database '$name' renamed to '$new'");
	}

	/**
	 * Gets names of tables in the given database
	 *
	 * @param string $database Database name
	 * @return array Tables in given database
	 */
	public static function list_tables($database) {
		$db = new Database;
		if (!$db->database_exists($database)) \Flight::halt(404, 'No such database');
		$tables = $db->get_tables($database);
		\Flight::json($tables);
	}

}
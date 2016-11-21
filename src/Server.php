<?php

/**
 * Front Controller for server requests and responses
 */
class Server {

	/**
	 * Welcome message
	 */
	public static function welcome() {
		$v = trim(file_get_contents(__DIR__ . '/../version'));
		header('X-Powered-By: SQLite Server v' . $v);
	}

	/**
	 * Gets all databases
	 *
	 * @return array List of database names, minus extensions
	 */
	public static function list_databases() {
		global $db_dir;
		$databases = scandir($db_dir);
		foreach ($databases as $key => $database) {
			if (substr($database, 0, 1) === '.')      unset($databases[$key]);
			if (substr($database, -8) === '-journal') unset($databases[$key]);
		}
		sort($databases, SORT_NATURAL | SORT_FLAG_CASE);
		API::json($databases);
	}

	/**
	 * Checks whether given database exists
	 *
	 * Only needs to instantiate the database object because it'll throw a 404 if database not found
	 */
	public static function database_exists($request) { new Database($request->database); }

	/**
	 * Gets names of tables in the given database
	 *
	 * @return array Tables in given database
	 */
	public static function list_tables($request) {
		$db     = new Database($request->database);
		$tables = $db->get_tables();
		API::json($tables);
	}

	/**
	 * Checks whether given table exists
	 */
	public static function table_exists($request) {
		$db = new Database($request->database);
		($db->table_exists($request->table)) ? API::send(200) : API::send(404);
	}

	/**
	 * Gets all data from the given table
	 *
	 * @return array Tables in given database
	 */
	public static function select_all($request) {
		$db = new Database;
		return $db->select_all($request->table);
	}

}

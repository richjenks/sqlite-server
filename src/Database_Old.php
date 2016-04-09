<?php namespace SQLiteServer;

/**
 * Provides a REST interface to SQLite databases
 */
class Database {

	/**
	 * @var object SQLite database handle
	 */
	private $db;

	/**
	 * @var string Path to databases directory
	 */
	private $dbs;

	/**
	 * Accepts options
	 *
	 * @param string $dbs Path to databases directory
	 */
	public function __construct($dbs) { $this->dbs = rtrim($dbs, '/') . '/'; }

	/**
	 * Turns a database name into a full file path
	 *
	 * @param string $name Database name
	 * @return string Full path to database file
	 */
	public function database($name) { return $this->dbs . $name . '.db'; }

	/**
	 * Validates a database name
	 *
	 * Can contain alphanumerics, hyphens, underscores and dots
	 * but must start with an alphanumeric
	 *
	 * @param string $name Desired database name
	 * @return bool Whether the name is valid
	 */
	public function validate_name($name) {
		return (preg_match('/^[a-zA-Z0-9][a-zA-Z0-9-_\.]+$/', $name)) ? true : false;
	}

	/**
	 * Checks whether the given database exists
	 *
	 * @param string $name Database name
	 * @return bool Whether the database exists
	 */
	public function database_exists($name) {
		return file_exists($this->dbs . $name . '.db');
	}

	/**
	 * Creates a new database, if name is valid and not used already
	 *
	 * @param string $database Database name
	 * @return bool Whether creation was succesful
	 */
	public function create_database($database) {
		$file = $this->dbs . $database . '.db';
		if (!$this->validate_name($file)) return false;
		if (file_exists($file)) return false;
		touch($file);
		return true;
	}

	/**
	 * Deletes a database, if it exists
	 *
	 * @param string $database Database name
	 * @return bool Whether deletion was succesful
	 */
	public function drop_database($database) {
		// In case name includes directory traversal, etc.
		if (!$this->validate_name($database)) return false;
		$file = $this->dbs . $database . '.db';
		if (!file_exists($file)) return false;
		unlink($file);
		return true;
	}

	/**
	 * Gets names of all databases
	 *
	 * @return array List of available databases
	 */
	public function get_databases() {

		$files = scandir(dirname(__DIR__) . '/databases');

		// Remove non-database files and remove extension from valid files
		foreach ($files as $key => $file) {
			if (substr($file, -3) !== '.db') {
				unset($files[$key]);
			} else {
				$files[$key] = substr($file, 0, -3);
			}
		}

		// Remove databases with invalid or reserved names
		foreach ($files as $key => $file) {
			if (!$this->validate_name($file)) unset($files[$key]);
		}

		// Put in usable order
		sort($files, SORT_NATURAL | SORT_FLAG_CASE);
		return $files;

	}

	/**
	 * Selects a database and creates a connection
	 *
	 * @param string $database Basename of SQLite database without extension
	 */
	public function use($database) {
		foreach ($this->extensions as $extension) {
			$path = $this->dbs . $database . '.' . $extension;
			if (file_exists($path)) {
				$this->db = new PDO('sqlite:' . $path);
				$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return true;
			}
		}
		return false;
	}

	/**
	 * Gets names of all tables in the current database
	 *
	 * @return array List of tables in current database
	 */
	// public function get_tables() {
	// 	$query = $this->db->prepare("SELECT name FROM sqlite_master WHERE type='table';");
	// 	$query->execute();
	// 	$result = $query->fetchAll(PDO::FETCH_ASSOC);
	// 	$tables = [];
	// 	foreach ($result as $table) {
	// 		if ($table['name'] !== 'sqlite_sequence') {
	// 			$tables[] = $table['name'];
	// 		}
	// 	}
	// 	return $tables;
	// }

	/**
	 * Builds a valid and sanitized SQL query from the HTTP request
	 *
	 * GET    = SELECT
	 * POST   = INSERT
	 * PUT    = UPDATE
	 * DELETE = DELETE
	 *
	 * @param datatype $paramname description
	 */
	// public function query_builder($table, $id = false) {

	// 	// Check table exists before constructing query
	// 	$tables = $this->get_tables();
	// 	if (!in_array($table, $tables)) Flight::halt(404, 'Error: Table not found!');

	// 	// Determine method
	// 	switch (Flight::request()->method) {
	// 		case 'GET':
	// 			$sql = 'SELECT';
	// 			break;

	// 		case 'POST':
	// 			$sql = 'INSERT';
	// 			break;

	// 		case 'PUT':
	// 			$sql = 'UPDATE';
	// 			break;

	// 		case 'DELETE':
	// 			$sql = 'DELETE';
	// 			break;

	// 		default:
	// 			$sql = false;
	// 			break;
	// 	}

	// 	$sql .= ' *';
	// 	$sql .= ' FROM';
	// 	$sql .= " $table";
	// 	if ($id) $sql .= " WHERE id = '$id'";

	// 	return $sql;

	// 	// $sql = "SELECT * FROM $table";
	// 	// $query = $this->db->prepare($sql);
	// 	// if ($id) $query->bindParam(':id', $id);
	// 	// return $query->queryString;

	// }

	/**
	 * Executes the given query and returns the result
	 *
	 * SELECT returns data
	 * INSERT returns ID of created row https://www.sqlite.org/c3ref/last_insert_rowid.html
	 * UPDATE & DELETE return number of rows affected
	 *
	 * @param string $sql Valid and sanitized SQL statement
	 * @return string/int JSON results of SELECT or number of rows affected for other command
	 */
	// public function get_results($sql) {
	// 	try {
	// 		if (substr($sql, 0, 6) === 'SELECT') {
	// 			$query = $this->db->query($sql);
	// 			$result = $query->fetchAll(PDO::FETCH_ASSOC);
	// 		} else {
	// 			// exec
	// 		}
	// 		return $result;
	// 	} catch (Exception $e) {
	// 		return $e;
	// 	}
	// }

}

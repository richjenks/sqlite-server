<?php namespace SQLiteServer;

/**
 * Basic database operations with no side-effects, e.g. output, status codes
 */
class Database {

	/**
	 * @var object PDO connection
	 */
	private $pdo;

	/**
	 * Opens a database connection
	 *
	 * @param string $database Name of database to use
	 */
	private function use($database) {
		$this->pdo = new \PDO('sqlite:' . $this->database_path($database));
		$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	/**
	 * Gets list of database names
	 *
	 * Excludes path and extension, just filenames
	 *
	 * @param string $path Path to databases directory
	 * @return array List of database names
	 */
	public function get_databases($path) {
		$files = scandir($path);

		// Remove non-database files
		foreach ($files as $key => $file)
			if (!$this->validate_database($file) || substr($file, -3) !== '.db')
				unset($files[$key]);

		// Remove extension
		foreach ($files as $key => $file) $files[$key] = substr($file, 0, -3);

		// Return readable list
		sort($files, SORT_NATURAL | SORT_FLAG_CASE);
		return $files;
	}

	/**
	 * Checks whether the given database exists
	 *
	 * @param string $name Database name
	 * @return bool Whether the database exists
	 */
	public function database_exists($name) { return file_exists($this->database_path($name)); }

	/**
	 * Determines whether a given database name is valid
	 *
	 * Can contain alphanumerics, hyphens, underscores and dots
	 * but must start with an alphanumeric
	 *
	 * Is wrapped in parenthesis because `preg_match()` may return int
	 *
	 * @param string $name Proposed database name
	 * @return bool Whether the name is valid
	 */
	public function validate_database($name) {
		return (preg_match('/^[a-zA-Z0-9][a-zA-Z0-9-_\.]*$/', $name)) ? true : false;
	}

	/**
	 * Gets the full path to a given database name
	 *
	 * @param string $name Database name
	 * @return string Full path to database file
	 */
	public function database_path($name) { return \Flight::get('dbs') . $name . '.db'; }

	/**
	 * Creates a new database
	 *
	 * @param string $name Database name
	 */
	public function create_database($name) { touch($this->database_path($name)); }

	/**
	 * Delets a database
	 *
	 * @param string $name Database name
	 */
	public function drop_database($name) { unlink($this->database_path($name)); }

	/**
	 * Renames a database
	 *
	 * @param string $name Current name of database
	 * @param string $new New name of database
	 */
	public function rename_database($name, $new) {
		rename($this->database_path($name), $this->database_path($new));
	}

	/**
	 * Gets list of table names in given database
	 *
	 * @param string $database Database name
	 * @return array List of table names
	 */
	public function get_tables($database) {
		$this->use($database);

		// Get table names
		$query  = $this->pdo->query("SELECT name FROM sqlite_master WHERE type='table';");
		$rows = $query->fetchAll(\PDO::FETCH_ASSOC);

		// Flatten results
		foreach ($rows as $row) $tables[] = $row['name'];

		// Return readable list
		sort($tables, SORT_NATURAL | SORT_FLAG_CASE);
		return $tables;
	}

}
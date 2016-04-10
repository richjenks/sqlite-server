<?php namespace SQLiteServer;

/**
 * Handles database creation and management
 */
class Database {

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

}
<?php

/**
 * Manages database interaction
 */
class Database {

	/**
	 * @var string Full path to database
	 */
	private $path;

	/**
	 * @var object PDO connection
	 */
	private $pdo;

	/**
	 * @var object SQL Query object
	 */
	private $query;

	/**
	 * Opens a database connection
	 *
	 * @param string $database Name of database to use
	 */
	public function __construct($database) {

		global $db_dir;
		$this->path = implode(DIRECTORY_SEPARATOR, [$db_dir, $database]);

		// Throw 404 if database not found
		if (!file_exists($this->path)) API::send(404, "Error: Database '$database' does not exist!");

		// Database handle
		$this->pdo = new \PDO('sqlite:' . $this->path);
		$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		// Query object for database interaction
		$this->query = new Query($this->pdo);

	}

	/**
	 * Gets list of table names in given database
	 *
	 * @return array List of tables in current database
	 */
	public function get_tables() {
		$result = $this->query->get_tables();
		$tables = [];
		foreach ($result as $table) $tables[] = $table['name'];
		if (!empty($tables)) sort($tables, SORT_NATURAL | SORT_FLAG_CASE);
		return $tables;
	}

	/**
	 * Checks whether the given table exists
	 *
	 * @param  string $table Table name
	 * @return bool   Whether the table exists
	 */
	public function table_exists($table) { return $this->query->table_exists($table); }

}

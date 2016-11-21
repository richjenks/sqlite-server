<?php

/**
 * Performs database queries
 */
class Query {

	/**
	 * @var object PDO handle
	 */
	private $db;

	/**
	 * Receives the database handle
	 *
	 * @param object $db PDO handle
	 */
	public function __construct($db) { $this->db = $db; }

	/**
	 * Gets list of tables in given handle
	 *
	 * @return array List of table names
	 */
	public function get_tables() {
		$query = $this->db->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%';");
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Checks whether the given table exists
	 *
	 * @param  string $table Table name
	 * @return bool   Whether the table exists
	 */
	public function table_exists($table) {
		$sql = "
			SELECT name
			FROM sqlite_master
			WHERE type = 'table'
			AND name = :table
			AND name NOT LIKE 'sqlite_%'
			LIMIT 1
		";

		$sth = $this->db->prepare($sql);
		$sth->execute([':table' => $table]);

		return !empty($sth->fetchAll(PDO::FETCH_ASSOC));
	}

}

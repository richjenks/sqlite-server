<?php

class DatabaseTest extends PHPUnit_Framework_TestCase {

	protected static $db;

	protected function setUp() {
		self::$db = new SQLiteServer\Database;
		Flight::set('dbs', dirname(dirname(__DIR__)) . '/databases/');
	}

	public function testGetDatabases() {
		$this->assertEmpty(self::$db->get_databases(Flight::get('dbs')));
		$databases = ['phpunit-test1', 'phpunit-test2', 'phpunit-test3'];
		foreach ($databases as $database) self::$db->create_database($database);
		$this->assertEquals(3, count(self::$db->get_databases(Flight::get('dbs'))));
		$this->assertEquals($databases, self::$db->get_databases(Flight::get('dbs')));
		foreach ($databases as $database) self::$db->drop_database($database);
	}

	public function testValidateDatabase() {
		$this->assertTrue(self::$db->validate_database('valid'));
		$this->assertTrue(self::$db->validate_database('Enterprise_Database'));
		$this->assertFalse(self::$db->validate_database('_invalid'));
		$this->assertFalse(self::$db->validate_database('!"£/'));
	}

	public function testDatabasePath() {
		$this->assertEquals(dirname(dirname(__DIR__)) . '/databases/exists.db', self::$db->database_path('exists'));
		$this->assertNotEquals(dirname(dirname(__DIR__)) . '/databases/exists.db', self::$db->database_path('nope'));
	}

	public function testCreateRenameDelete() {
		$this->assertFalse(file_exists(self::$db->database_path('phpunit-test')));

		self::$db->create_database('phpunit-test');
		$this->assertTrue(file_exists(self::$db->database_path('phpunit-test')));

		self::$db->rename_database('phpunit-test', 'phpunit-renamed');
		$this->assertFalse(file_exists(self::$db->database_path('phpunit-test')));
		$this->assertTrue(file_exists(self::$db->database_path('phpunit-renamed')));

		self::$db->drop_database('phpunit-renamed');
		$this->assertFalse(file_exists(self::$db->database_path('phpunit-renamed')));
	}

}
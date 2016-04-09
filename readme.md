# SQLite Server

SQLite Server is a drop-in REST API for SQLite written in PHP

## Endpoints

### `HEAD /`: Server test

Will return the header `Welcome: SQLite Server v0.0.1` if successful.

### `GET /`: List databases

Returns an array of all available databases.

### `HEAD /@database`: Check if database exists

Status will be 200 if database exists and 404 if not.

### `POST /@database`: Create database

Database names must start with an alphanumeric character but may also contain hyphens, underscores and periods.

### `DELETE /@database`: Delete database

Be careful as there are no confirmations or checks before deleting a database.

## Unit Tests

Test with `php vendor/phpunit/phpunit/phpunit tests/src --bootstrap vendor/autoload.php`

Replace `tests/src` with `tests/api http://localhost:8301/` to test the API
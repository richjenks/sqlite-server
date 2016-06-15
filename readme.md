# SQLite Server

SQLite Server is a drop-in REST API for SQLite written in PHP

_Requires PHP 5.5 with PDO extension_

## Installation

1. `git clone git@github.com:richjenks/sqlite-server.git`
1. `composer install --no-dev`
1. Make the `public` directory accessible via HTTP, e.g. `php -S localhost:8008 /path/to/public`

## Endpoints

### Server test

Checks the server is running.

- Request
	- URL: `HEAD /`
- Response
	- Status: `200`
	- Header: `Welcome: SQLite Server v0.0.1`

### List databases

Returns an array of all available databases.

- Request
	- URL: `GET /`
- Response
	- Status: `200`
	- Body: `['database1', 'database2']`

### Check database

Checks whether a given database exists.

- Request
	- URL: `HEAD /:database`
- Response
	- Status: `200` if exists, `404` if not

### Create database

Database names must start with an alphanumeric character but may also contain hyphens, underscores and periods.

- Request
	- URL: `POST /:database`
- Response
	- Status: `201` if successful, `409` if already exists or name invalid
	- Body: `Database 'database_name' created`

### Rename database

New database name should go in request body in plaintext.

- Request
	- URL: `PUT /:database`
	- Body: `new_database_name`
- Response
	- Status: `201` if successful, `409` if name invalid
	- Body: `Database 'database_name' renamed to 'new_name'`

### Delete database

Be careful as there are no confirmations or checks before deleting a database.

- Request
	- URL: `DELETE /:database`
- Response
	- Status: `200` if successful, `404` if database doesn't exist
	- Body: `Database 'database_name' deleted`

## Unit Tests

1. `git clone git@github.com:richjenks/sqlite-server.git`
1. `composer install`
1. `php vendor/phpunit/phpunit/phpunit tests --bootstrap vendor/autoload.php`

## Todo

- [ ] Design endpoints
- [ ] Database CRUD
- [ ] Table CRUD - how to differentiate between create table and create row?
- [ ] SQL statement construction
- [ ] SQL execution
- [ ] HTTP Basic Auth...but where to store creds/permissions...?
- [ ] Permissions: CRUD option at database-data-level

# SQLite Server

SQLite Server provides a RESTful interface for remote SQLite databases

_Requires PHP 7+ with PDO extension_

## Getting Started

1. Download/clone this repository
1. Install dependencies: `composer install`
1. Make the `public` directory accessible via HTTP, e.g. `php -S localhost:8008 -t public public/index.php`
1. Any file in the `databases` folder will be treated as an SQLite database

## Server Configuration

.htaccess, .htpassword, etc.

## Features

- Use SQLite databases hosted remotely
- Create and delete databases
- Manage tables and columns
- Data...
- ETag and `If-None-Match` support

RESTful API to...

SQLite Server has no concept of authentication by design so you are free to implement authentication

Also doesnt do database schema, just data. Not a full SQL implementation, just CRUD (SELECT, INSERT, UPDATE, DELETE)
Arbitrary commands are supported

Not actually a server as such — the word "server" is used only to imply the provision of remote access similar to MySQL Server or MS SQL Server.

## Unsupported

SQLite Server intentionally omits any concept of authentication or authorisation

## Notes

SQLite Server doesn't check whether a file in the `databases` folder is an SQLite database, it just ignores dotfiles.

## Documentation

Full API documentation can be found in the `documentation.md` file in markdown format.

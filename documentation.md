# SQLite Server API Documentation

## Welcome

Shows API welcome message

### Request

- **URL**: HEAD /

### Response

- `200`: Server is available

## List Databases

Get a list of available databases

### Request

- **URL**: GET /

### Response

- `200`: Array of database names

## Check Database Exists

Checks whether the given database exists in the `databases` folder

### Request

- **URL**: HEAD /{db}

### Response

- `200`: Database exists
- `404`: Database not found

## List Tables

Get a list of tables in given database

### Request

- **URL**: GET /{db}

### Response

- `200`: Array of database names
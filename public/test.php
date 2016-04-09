<?php

require '../vendor/autoload.php';
$server = new SQLiteServer\Server;
var_dump($server->get_databases());
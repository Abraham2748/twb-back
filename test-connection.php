<?php

$current = dirname(__FILE__);
$data = file_get_contents($current . "/" . "server-config.json");
$config = json_decode($data, true);
$connection = $config["connection"];

$server = $connectionData['server'];
$user = $connectionData['user'];
$password = $connectionData['password'];
$database = $connectionData['database'];
$port = $connectionData['port'];

$connection = new mysqli($server, $user, $password, $database, $port);
if ($this->connection->connect_errno) {
    echo "Error connecting";
    die();
}
echo "Connected";

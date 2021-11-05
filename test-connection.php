<?php

$current = dirname(__FILE__);
$data = file_get_contents($current . "/" . "server-config.json");
echo json_decode($data, true);

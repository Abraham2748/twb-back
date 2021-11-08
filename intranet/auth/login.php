<?php

require_once('../../connection.php');
require_once('../../responses.php');

$connection = new Connection();
$responses = new Responses();

header('Content-type: application/json');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postBody = file_get_contents("php://input");
    $data = $_responses->ok('correct method');
} else {
    $data = $_responses->error_405();
}

echo json_encode($data);

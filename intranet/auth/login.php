<?php

require_once('../../connection.php');
require_once('../../responses.php');

$connection = new Connection();
$responses = new Responses();

header('Content-type: application/json');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postBody = json_decode(file_get_contents("php://input"), true);
    $data = $responses->ok($postBody);
} else {
    $data = $responses->error_405();
}

echo json_encode($data);

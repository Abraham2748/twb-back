<?php

require_once('../../connection.php');
require_once('../../responses.php');

$connection = new Connection();
$responses = new Responses();

header('Content-type: application/json');
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $postBody = json_decode(file_get_contents("php://input"), true);
        $data = $responses->ok($postBody);
        break;
    case 'GET':
        $postBody = json_decode(file_get_contents("php://input"), true);
        $data = $responses->ok($postBody);
        break;
    case 'PUT':
        $postBody = json_decode(file_get_contents("php://input"), true);
        $data = $responses->ok($postBody);
        break;
    case 'DELETE':
        $postBody = json_decode(file_get_contents("php://input"), true);
        $data = $responses->ok($postBody);
        break;
    default:
        $data = $responses->error_405();
        break;
}

echo json_encode($data);

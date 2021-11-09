<?php

require_once('../../connection.php');
require_once('../../responses.php');

$connection = new Connection();
$responses = new Responses();

header('Content-type: application/json');
$postBody = json_decode(file_get_contents("php://input"), true);
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $data = $responses->ok($postBody);
        break;
    case 'GET':
        $data = $responses->ok($postBody);
        break;
    case 'PUT':
        $data = $responses->ok($postBody);
        break;
    case 'DELETE':
        $data = $responses->ok($postBody);
        break;
    default:
        $data = $responses->error_405();
        break;
}

echo json_encode($data);

<?php

require_once('../../connection.php');
require_once('../../responses.php');

$connection = new Connection();
$responses = new Responses();

header('Content-type: application/json');
$postBody = json_decode(file_get_contents("php://input"), true);
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if (validateUser($postBody)) {
            $data = $responses->ok(':)');
        } else {
            $data = $responses->ok(':(');
        }
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


function validateUser($user)
{
    return isset($user["username"]) && isset($user["password"])
        && isset($user["firstName"]) && isset($user["lastName"])
        && isset($user["documentNumber"]);
}

echo json_encode($data);

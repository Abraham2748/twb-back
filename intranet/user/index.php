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
            $data = addUser($postBody);
        } else {
            $data = $responses->error_400();
        }
        break;
    case 'GET':
        if (isset($_GET['id'])) {
            $data = getUser($_GET['id']);
        } else if (isset($_GET['page']) && isset($_GET['rowsPerPage'])) {
            $data = getUsers($_GET['page'], $_GET['rowsPerPage']);
        } else {
            $data = $responses->error_400();
        }
        break;
    case 'PUT':
        if (isset($_GET['id']) && validateUser($postBody)) {
            $data = updateUser($_GET['id'], $postBody);
        } else {
            $data = $responses->error_400();
        }
        break;
    case 'DELETE':
        if (isset($_GET['id'])) {
            $data = deleteUser($_GET['id']);
        } else {
            $data = $responses->error_400();
        }
        break;
    default:
        $data = $responses->error_405();
        break;
}

function deleteUser($id)
{
    global $connection;
    global $responses;
    $result = $connection->callProcedure('SP_USER_DELETE', array(
        '_id' => $id,
    ));
    return $responses->ok($result);
}

function updateUser($id, $user)
{
    global $connection;
    global $responses;
    $result = $connection->callProcedure('SP_USER_UPDATE', array(
        '_id' => $id,
        '_idGender' => $user['idGender'],
        '_username' => $user['username'],
        '_password' => $user['password'],
        '_firstName' => $user['firstName'],
        '_lastName' => $user['lastName'],
        '_documentNumber' => $user['documentNumber'],
    ));
    return $responses->ok($result);
}

function getUsers($page, $rowsPerPage)
{
    global $connection;
    global $responses;
    $result = $connection->callProcedure('SP_USER_GET_LIST', array(
        '_page' => $page,
        '_rowsPerPage' => $rowsPerPage,
    ));
    if (count($result) > 0) {
        return $responses->ok($result);
    } else {
        return $responses->error_404("Users not found");
    }
}

function getUser($id)
{
    global $connection;
    global $responses;
    $result = $connection->callProcedure('SP_USER_GET', array(
        '_id' => $id,
    ));
    if (count($result) == 1) {
        return $responses->ok($result[0]);
    } else {
        return $responses->error_404("User not found");
    }
}

function addUser($user)
{
    global $connection;
    global $responses;
    $result = $connection->callProcedure('SP_USER_ADD', array(
        '_idGender' => $user['idGender'],
        '_username' => $user['username'],
        '_password' => $user['password'],
        '_firstName' => $user['firstName'],
        '_lastName' => $user['lastName'],
        '_documentNumber' => $user['documentNumber'],
    ));
    return $responses->ok($result);
}

function validateUser($user)
{
    return isset($user["username"]) && isset($user["password"])
        && isset($user["firstName"]) && isset($user["lastName"])
        && isset($user["documentNumber"]) && isset($user["idGender"]);
}

echo json_encode($data);

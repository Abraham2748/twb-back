<?php

require_once('../../connection.php');
require_once('../../responses.php');

$connection = new Connection();
$responses = new Responses();

$connection->authenticate();
$postBody = json_decode(file_get_contents("php://input"), true);
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if (validateBarber($postBody)) {
            $data = addBarber($postBody);
        } else {
            $data = $responses->error_400();
        }
        break;
    case 'GET':
        if (isset($_GET['id'])) {
            $data = getBarber($_GET['id']);
        } else if (isset($_GET['page']) && isset($_GET['rowsPerPage'])) {
            $data = getBarbers($_GET['page'], $_GET['rowsPerPage']);
        } else {
            $data = $responses->error_400();
        }
        break;
    case 'PUT':
        if (isset($_GET['id']) && validateBarber($postBody)) {
            $data = updateBarber($_GET['id'], $postBody);
        } else {
            $data = $responses->error_400();
        }
        break;
    case 'DELETE':
        if (isset($_GET['id'])) {
            $data = deleteBarber($_GET['id']);
        } else {
            $data = $responses->error_400();
        }
        break;
    default:
        $data = $responses->error_405();
        break;
}

function deleteBarber($id)
{
    global $connection;
    global $responses;
    $result = $connection->callProcedure('SP_BARBER_DELETE', array(
        '_id' => $id,
    ));
    return $responses->ok($result);
}

function updateBarber($id, $barber)
{
    global $connection;
    global $responses;
    $result = $connection->callProcedure('SP_BARBER_UPDATE', array(
        '_id' => $id,
        '_idGender' => $barber['idGender'],
        '_firstName' => $barber['firstName'],
        '_lastName' => $barber['lastName'],
        '_documentNumber' => $barber['documentNumber'],
        '_photo' => $barber['photo'],
    ));
    return $responses->ok($result);
}

function getBarbers($page, $rowsPerPage)
{
    global $connection;
    global $responses;
    $result = $connection->callProcedure('SP_BARBER_GET_LIST', array(
        '_page' => $page,
        '_rowsPerPage' => $rowsPerPage,
    ));
    if (count($result) > 0) {
        return $responses->ok($result);
    } else {
        return $responses->error_404("Barbers not found");
    }
}

function getBarber($id)
{
    global $connection;
    global $responses;
    $result = $connection->callProcedure('SP_BARBER_GET', array(
        '_id' => $id,
    ));
    if (count($result) == 1) {
        return $responses->ok($result[0]);
    } else {
        return $responses->error_404("Barber not found");
    }
}

function addBarber($barber)
{
    global $connection;
    global $responses;
    $result = $connection->callProcedure('SP_BARBER_ADD', array(
        '_idGender' => $barber['idGender'],
        '_firstName' => $barber['firstName'],
        '_lastName' => $barber['lastName'],
        '_documentNumber' => $barber['documentNumber'],
        '_photo' => $barber['photo'],
    ));
    return $responses->ok($result);
}

function validateBarber($barber)
{
    return isset($barber["idGender"])
        && isset($barber["firstName"])
        && isset($barber["lastName"])
        && isset($barber["documentNumber"])
        && isset($barber["photo"]);
}

echo json_encode($data);

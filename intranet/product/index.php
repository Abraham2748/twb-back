<?php

require_once('../../connection.php');
require_once('../../responses.php');

$connection = new Connection();
$responses = new Responses();

$connection->authenticate();
$postBody = json_decode(file_get_contents("php://input"), true);
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if (validateProduct($postBody)) {
            $data = addProduct($postBody);
        } else {
            $data = $responses->error_400();
        }
        break;
    case 'GET':
        if (isset($_GET['id'])) {
            $data = getProduct($_GET['id']);
        } else if (isset($_GET['page']) && isset($_GET['rowsPerPage'])) {
            $data = getProducts($_GET['page'], $_GET['rowsPerPage']);
        } else {
            $data = $responses->error_400();
        }
        break;
    case 'PUT':
        if (isset($_GET['id']) && validateProduct($postBody)) {
            $data = updateProduct($_GET['id'], $postBody);
        } else {
            $data = $responses->error_400();
        }
        break;
    case 'DELETE':
        if (isset($_GET['id'])) {
            $data = deleteProduct($_GET['id']);
        } else {
            $data = $responses->error_400();
        }
        break;
    default:
        $data = $responses->error_405();
        break;
}

function deleteProduct($id)
{
    global $connection;
    global $responses;
    $result = $connection->callProcedure('SP_PRODUCT_DELETE', array(
        '_id' => $id,
    ));
    return $responses->ok($result);
}

function updateProduct($id, $product)
{
    global $connection;
    global $responses;
    $result = $connection->callProcedure('SP_PRODUCT_UPDATE', array(
        '_id' => $id,
        '_idProductCategory' => $product['idProductCategory'],
        '_name' => $product['name'],
        '_price' => $product['price'],
        '_photo' => $product['photo'],
    ));
    return $responses->ok($result);
}

function getProducts($page, $rowsPerPage)
{
    global $connection;
    global $responses;
    $result = $connection->callProcedure('SP_PRODUCT_GET_LIST', array(
        '_page' => $page,
        '_rowsPerPage' => $rowsPerPage,
    ));
    if (count($result) > 0) {
        return $responses->ok($result);
    } else {
        return $responses->error_404("Products not found");
    }
}

function getProduct($id)
{
    global $connection;
    global $responses;
    $result = $connection->callProcedure('SP_PRODUCT_GET', array(
        '_id' => $id,
    ));
    if (count($result) == 1) {
        return $responses->ok($result[0]);
    } else {
        return $responses->error_404("Product not found");
    }
}

function addProduct($product)
{
    global $connection;
    global $responses;
    $result = $connection->callProcedure('SP_PRODUCT_ADD', array(
        '_idProductCategory' => $product['idProductCategory'],
        '_name' => $product['name'],
        '_price' => $product['price'],
        '_photo' => $product['photo'],
    ));
    return $responses->ok($result);
}

function validateProduct($product)
{
    return isset($product["idProductCategory"])
        && isset($product["name"])
        && isset($product["price"])
        && isset($product["photo"]);
}

echo json_encode($data);

<?php

require_once('../../connection.php');
require_once('../../responses.php');

$connection = new Connection();
$responses = new Responses();

header('Content-type: application/json');
$connection->authenticate();
$postBody = json_decode(file_get_contents("php://input"), true);
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if (validateProductCategory($postBody)) {
            $data = addProductCategory($postBody);
        } else {
            $data = $responses->error_400();
        }
        break;
    case 'GET':
        if (isset($_GET['id'])) {
            $data = getProductCategory($_GET['id']);
        } else if (isset($_GET['page']) && isset($_GET['rowsPerPage'])) {
            $data = getProductCategories($_GET['page'], $_GET['rowsPerPage']);
        } else {
            $data = $responses->error_400();
        }
        break;
    case 'PUT':
        if (isset($_GET['id']) && validateProductCategory($postBody)) {
            $data = updateProductCategory($_GET['id'], $postBody);
        } else {
            $data = $responses->error_400();
        }
        break;
    case 'DELETE':
        if (isset($_GET['id'])) {
            $data = deleteProductCategory($_GET['id']);
        } else {
            $data = $responses->error_400();
        }
        break;
    default:
        $data = $responses->error_405();
        break;
}

function deleteProductCategory($id)
{
    global $connection;
    global $responses;
    $result = $connection->callProcedure('SP_PRODUCT_CATEGORY_DELETE', array(
        '_id' => $id,
    ));
    return $responses->ok($result);
}

function updateProductCategory($id, $productCategory)
{
    global $connection;
    global $responses;
    $result = $connection->callProcedure('SP_PRODUCT_CATEGORY_UPDATE', array(
        '_id' => $id,
        '_name' => $productCategory['name'],
        '_photo' => $productCategory['photo'],
    ));
    return $responses->ok($result);
}

function getProductCategories($page, $rowsPerPage)
{
    global $connection;
    global $responses;
    $result = $connection->callProcedure('SP_PRODUCT_CATEGORY_GET_LIST', array(
        '_page' => $page,
        '_rowsPerPage' => $rowsPerPage,
    ));
    if (count($result) > 0) {
        return $responses->ok($result);
    } else {
        return $responses->error_404("Product Categories not found");
    }
}

function getProductCategory($id)
{
    global $connection;
    global $responses;
    $result = $connection->callProcedure('SP_PRODUCT_CATEGORY_GET', array(
        '_id' => $id,
    ));
    if (count($result) == 1) {
        return $responses->ok($result[0]);
    } else {
        return $responses->error_404("Product Category not found");
    }
}

function addProductCategory($productCategory)
{
    global $connection;
    global $responses;
    $result = $connection->callProcedure('SP_PRODUCT_CATEGORY_ADD', array(
        '_name' => $productCategory['name'],
        '_photo' => $productCategory['photo'],
    ));
    return $responses->ok($result);
}

function validateProductCategory($product)
{
    return isset($product["name"])
        && isset($product["photo"]);
}

echo json_encode($data);

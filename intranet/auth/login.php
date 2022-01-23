<?php

require_once('../../connection.php');
require_once('../../responses.php');

$connection = new Connection();
$responses = new Responses();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postBody = json_decode(file_get_contents("php://input"), true);
    if (isset($postBody['username']) && isset($postBody["password"])) {
        $username = $postBody["username"];
        $password = $postBody["password"];
        $userData = getUserData($username, $password);
        if ($userData) {
            $data = $responses->ok($userData);
        } else {
            $data = $responses->error_200("Wrong username or password");
        }
    } else {
        $data = $_responses->error_400();
    }
} else {
    $data = $responses->error_405();
}

function getUserData($email, $password)
{
    global $connection;
    $result = $connection->callProcedure('SP_AUTH_LOGIN', array(
        '_username' => $email,
        '_password' => $password
    ));
    if (count($result) == 1) {
        return $result[0];
    } else {
        return null;
    }
}

echo json_encode($data);

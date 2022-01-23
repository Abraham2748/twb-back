<?php

header('Content-type: application/json');

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(0);
}


class Connection
{
    private $server;
    private $user;
    private $password;
    private $database;
    private $port;

    private $connection;

    function __construct()
    {
        $configList = $this->configList();
        $connectionData = $configList["connection"];
        $this->server = $connectionData['server'];
        $this->user = $connectionData['user'];
        $this->password = $connectionData['password'];
        $this->database = $connectionData['database'];
        $this->port = $connectionData['port'];
        $this->connection = new mysqli($this->server, $this->user, $this->password, $this->database, $this->port);
        if ($this->connection->connect_errno) {
            echo "Error connecting";
            die();
        }
    }

    private function configList()
    {
        $current = dirname(__FILE__);
        $data = file_get_contents($current . "/" . "server-config.json");
        return json_decode($data, true);
    }

    public function callProcedure($procedureName, $parameters)
    {
        $sql = "CALL " . $procedureName . "(";
        foreach ($parameters as $key => $value) {
            $sql .= "'" . $value . "',";
        }
        $sql = substr($sql, 0, -1);
        $sql .= ")";
        $results = $this->connection->query($sql);
        $resultArray = array();
        foreach ($results as $key => $value) {
            $resultArray[] = $value;
        }
        mysqli_next_result($this->connection);
        return $resultArray;
    }

    public function authenticate()
    {
        $headers = getallheaders();
        $authorized = false;
        if (isset($headers["Authorization"])) {
            $result = $this->callProcedure('SP_AUTH_VALIDATE_TOKEN', array(
                '_token' => substr($headers["Authorization"], 7),
            ));
            $authorized = $result[0]['Authorize'] == 1;
        }

        if (!$authorized) {
            $error401 = array(
                'status' => 'Unauthorized',
                'result' => array(
                    'error_id' => '401',
                    'message' => 'Invalid or expired token'
                )
            );
            echo json_encode($error401);
            http_response_code(401);
            die();
        }
    }
}

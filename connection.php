<?php

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
        return $resultArray;
    }

    public function authenticate($error401)
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
            echo json_encode($error401);
            http_response_code(401);
            die();
        }
    }
}

<?php

class Responses
{

    public $response = [
        "status" => "ok",
        "result" => null
    ];

    public function ok($result)
    {
        $this->response["status"] = "ok";
        $this->response["result"] = $result;
        http_response_code(200);
        return $this->response;
    }

    public function error_405()
    {
        $this->response["status"] = "error";
        $this->response["result"] = array(
            "error_id" => "405",
            "error_msg" => "Method Not Allowed",
        );
        http_response_code(405);
        return $this->response;
    }

    public function error_200($message = "Incorrect data")
    {
        $this->response["status"] = "error";
        $this->response["result"] = array(
            "error_id" => "200",
            "error_msg" => $message,
        );
        http_response_code(200);
        return $this->response;
    }

    public function error_400()
    {
        $this->response["status"] = "error";
        $this->response["result"] = array(
            "error_id" => "400",
            "error_msg" => "Incomplete data",
        );
        http_response_code(400);
        return $this->response;
    }

    public function error_404($message = "The requested resource was not found.")
    {
        $this->response["status"] = "error";
        $this->response["result"] = array(
            "error_id" => "404",
            "error_msg" => $message,
        );
        http_response_code(404);
        return $this->response;
    }


    public function error_500()
    {
        $this->response["status"] = "error";
        $this->response["result"] = array(
            "error_id" => "500",
            "error_msg" => "Internal Server Error",
        );
        http_response_code(500);
        return $this->response;
    }
}

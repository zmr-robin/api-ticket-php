<?php

namespace App\Routes;
use App\Controllers\AuthController;
use App\Controllers\UserController;

class Router {

    private $request;
    public $data;
    private $status = 200;
    private $method;

    function __construct($request, $method){
        $request = explode("/", $request);
        $this->request = $request;
        $this->method = $method;
        $this->useController();

    }

    private function useController(){
        switch ($this->request[0]){
            case "auth":
                $controller = new AuthController($this->request);
                $this->data = $controller->data;
                break;
            case "message":
                echo "soon";
                break;
            case "role":
                echo "soon";
                break;
            case "tag":
                echo "soon";
                break;
            case "ticket":
                echo "soon";
                break;
            case "users":
                $controller = new UserController($this->request, $this->method);
                $this->data = $controller->data;
                break;
            default:
            $this->status = 404;
                $this->data = [
                    "status" => $this->status,
                    "content" => "No service named '" . $this->request[0] . "' found!"
                ];
                break;
                
        }
    }

}
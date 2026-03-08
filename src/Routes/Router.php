<?php

namespace App\Routes;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Middleware\Middleware;
use App\Exceptions\Exceptions;

class Router
{

    private $request;
    public $data;
    private $status = 200;
    private $method;

    function __construct($request, $method)
    {
        $request = explode("/", $request);
        $this->request = $request;
        $this->method = $method;
        $this->useController();

    }

    private function useController()
    {
        switch ($this->request[0]) {
            case "auth":
                $controller = new AuthController($this->request);
                $this->data = $controller->data;
                break;
            case "message":
                $this->data = ["status" => 501, "content" => "Message service coming soon"];
                break;
            case "role":
                $this->data = ["status" => 501, "content" => "Role service coming soon"];
                break;
            case "tag":
                $this->data = ["status" => 501, "content" => "Tag service coming soon"];
                break;
            case "ticket":
                $this->data = ["status" => 501, "content" => "Ticket service coming soon"];
                break;
            case "users":
                $controller = new UserController($this->request, $this->method);
                $this->data = $controller->data;
                break;
            default:
                Exceptions::notFound();
                break;

        }
    }

}
<?php

namespace App\Routes;
use App\Controllers\ArchiveController;
use App\Controllers\AuthController;
use App\Controllers\TagController;
use App\Controllers\TicketController;
use App\Controllers\UserController;
use App\Controllers\RoleController;
use App\Controllers\EmailController;
use App\Controllers\MessageController;
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
                $controller = new AuthController($this->request, $this->method);
                $this->data = $controller->data;
                break;
            case "messages":
                $controller = new MessageController($this->request, $this->method);
                $this->data = $controller->data;
                break;
            case "roles":
                $controller = new RoleController($this->request, $this->method);
                $this->data = $controller->data;
                break;
            case "emails":
                $controller = new EmailController($this->request, $this->method);
                $this->data = $controller->data;
                break;
            case "tags":
                $controller = new TagController($this->request, $this->method);
                $this->data = $controller->data;
                break;
            case "tickets":
                $controller = new TicketController($this->request, $this->method);
                $this->data = $controller->data;
                break;
            case "users":
                $controller = new UserController($this->request, $this->method);
                $this->data = $controller->data;
                break;
            case "archive":
                $controller = new ArchiveController($this->request, $this->method);
                $this->data = $controller->data;
                break;
            default:
                Exceptions::notFound();
                break;

        }
    }

}
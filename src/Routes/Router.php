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

    private $request; // Request url
    public $data; // Return data
    private $method; // Server request method

    function __construct($request, $method)
    {
        $request = explode("/", $request);
        $this->request = $request;
        $this->method = $method;
        $this->redirect(); // Use redirect method to process request

    }

    // Decides to which endpoint (controller) the request should be directed
    private function redirect()
    {
        switch ($this->request[0]) {
            case "auth":
                $controller = new AuthController($this->request, $this->method);
                $this->data = $controller->data; // Get data of auth controller (result of the request) 
                break;
            case "messages":
                // Validates the api key        
                Middleware::checkIfKeyIsValid();
                $controller = new MessageController($this->request, $this->method);
                $this->data = $controller->data;
                break;
            case "roles":
                Middleware::checkIfKeyIsValid();
                $controller = new RoleController($this->request, $this->method);
                $this->data = $controller->data;
                break;
            case "emails":
                Middleware::checkIfKeyIsValid();
                $controller = new EmailController($this->request, $this->method);
                $this->data = $controller->data;
                break;
            case "tags":
                Middleware::checkIfKeyIsValid();
                $controller = new TagController($this->request, $this->method);
                $this->data = $controller->data;
                break;
            case "tickets":
                Middleware::checkIfKeyIsValid();
                $controller = new TicketController($this->request, $this->method);
                $this->data = $controller->data;
                break;
            case "users":
                Middleware::checkIfKeyIsValid();
                $controller = new UserController($this->request, $this->method);
                $this->data = $controller->data;
                break;
            case "archive":
                Middleware::checkIfKeyIsValid();            
                $controller = new ArchiveController($this->request, $this->method);
                $this->data = $controller->data;
                break;
            default:
                Exceptions::notFound();
                break;

        }
    }

}
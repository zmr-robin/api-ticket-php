<?php

namespace App\Controllers;

use App\Exceptions\Exceptions;
use App\Services\AuthService;

class AuthController {

    public $data; // Request url
    private $request; // Return data
    private $auth; // AuthService object: processes request data
    private $method; // Server request method

    function __construct($request, $method){
        
        $this->request = $request;
        $this->method = $method;
        $this->auth = new AuthService($request);

        // Use function based on request method
        switch($this->method){
            case "GET":
                $this->data = $this->methodGet();
                break;
            case "POST":
                $this->data = $this->methodPost();
                break;
            case "PUT":
                $this->data = $this->methodPut();
                break;
            case "DELETE":
                $this->data = $this->methodDelete();
                break;
            default:
                break; // ! TODO ERROR
        }

    }

    /* =================
    
    GET 
        All keys:                   /auth/
        Spesific key data:          /auth/{ID}
        
      =================*/
    private function methodGet(){
        if(isset($this->request[1]) && count($this->request) == 2){
            return $this->auth->getData();
        } else if (count($this->request) == 1) {
            return $this->auth->get();
        } else {
            Exceptions::badRequest();
        }
    }

    /* =================
    
    Post 
        Authentification of user    /auth/

      =================*/
    private function methodPost(){
        if (count($this->request) == 1){
            return $this->auth->auth();
        }
    }

    /* =================
    
    Put 
        ! Change duration of key    /auth/{ID}/duration
        
      =================*/
    private function methodPut(){
        if(isset($this->request[1]) && isset($this->request[2])){
            if ($this->request[2] == "duration"){
                return $this->auth->duration();
            }
        } else {
            Exceptions::notFound();
        }
    }

    /* =================
    
    Delete 
        ! Delete key               /auth/{ID}/delete
        
      =================*/
    private function methodDelete(){
        if(isset($this->request[1])){
            if ($this->request[1] == "users" ){
                if(isset($this->request[2]) && $this->request[2] == "delete"){
                    $this->auth->deleteUser();
                } else {
                    Exceptions::notFound();
                }
            } else {
                $this->auth->delete();
            }
        } else {
            Exceptions::notFound();
        }
    }
}
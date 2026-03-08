<?php

namespace App\Controllers;

use App\Exceptions\Exceptions;
use App\Services\AuthService;

class AuthController {

    public $data;
    private $request;
    private $auth;
    private $method;

    function __construct($request, $method){
        
        $this->request = $request;
        $this->method = $method;
        $this->auth = new AuthService($request);

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
        ! All keys:             /auth/
        ! Key Data:             /auth/{ID}/data
        
      =================*/
    private function methodGet(){

    }

    /* =================
    
    Post 
        ! Auth user             /auth/
        ! Auth for ID           /auth/users/{UserID}/
        
      =================*/
    private function methodPost(){
        if (isset($this->request[1]) && isset($this->request[2])){
            return $this->auth->authByID();
        } else {
            return $this->auth->auth();
        }
    }

    /* =================
    
    Put 
        ! Auth change duration  /auth/{ID}/duration
        
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
        ! Delete auth key       /auth/{ID}/delete
        ! Delete auth key by id /auth/users/{UserID}/delete
        
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
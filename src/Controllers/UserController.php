<?php

namespace App\Controllers;
use App\Services\UserService;
use App\Exceptions\Exceptions;

class UserController{
    public $data;
    private $request;
    private $method;
    private $usr;

    function __construct($request, $method){
        
        $this->request = $request;
        $this->usr = new UserService($this->request);
        $this->method = $method;
        
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
    
    Post Data 
        create:  /users/create
        invite: /users/invite // ! TODO Email notify
        
      =================*/
    private function methodPost(){
        if(isset($this->request[1])){
            switch($this->request[1]){
                // Get (all) data for specific user
                case "create":
                    return $this->usr->create();
                case "invite":
                    $this->usr->invite();
                default:
                    Exceptions::badRequest();
            }
        }
    }

    /* =================
    
    GET Data 
        List all users:  /users/
    
    By user id
        All data:       /users/{id}/
        Role of user:   /users/{id}/role/

        !TODO Email of user:  /users/{id}/email/
        !TODO Auth of user:   /users/{id}/auth/
        !TODO Level of user:  /users/{id}/level/
        
      =================*/
    private function methodGet(){
        if(isset($this->request[1])){
            if (isset($this->request[2])){
                switch($this->request[2]){
                    case "role":
                        return $this->usr->getRole();
                    case "email":
                        return $this->usr->getEmail();
                    case "level":
                        return $this->usr->getLevel();
                    case "auth":
                        return $this->usr->getAuth();
                    default: 
                        Exceptions::notFound();
                }
            } else {
                return $this->usr->getData();
            }
        } else {
            return $this->usr->get();
        }
    }

    /* =================
    
    Delete Data 
        User:  /users/{id}/

    =================*/
    private function methodDelete(){
        if(isset($this->request[1])){
            return $this->usr->delete();
        }
    }

    /* =================
    
    Update Data 
        Role:  /users/{id}/role
        
    =================*/
    private function methodPut(){
        if(isset($this->request[2]) && isset($this->request[1])){
            switch($this->request[2]){
                // Change user role
                case "role":
                    return $this->usr->setRole();
            }
        } 
    }


}
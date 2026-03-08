<?php

namespace App\Controllers;
use App\Services\UserService;

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
                break;
            default:
                break; // ! TODO ERROR
        }

    }

    /* =================
    
    Post Data 
        create:  /users/create
        invite: /users/invite
        
      =================*/
    private function methodPost(){
        if(isset($this->request[1])){
            switch($this->request[1]){
                // Get (all) data for specific user
                case "create":
                    return $this->usr->createUser();
                case "invite":
                    $this->usr->inviteUser();
            }
        }
    }

    /* =================
    
    GET Data 
        List all users:  /users/
    
    By user id
        All data:       /users/{id}/
        Role of user:   /users/{id}/role/

        !TODO Email of user:  /users/email/{id}
        
      =================*/
    private function methodGet(){
        if(isset($this->request[1])){
            if (isset($this->request[2])){
                switch($this->request[2]){
                    // Get role of user
                    case "role":
                        return $this->usr->getUserRole();
                    // Get email of user
                    case "email":
                        break;
                }
            } else {
                return $this->usr->getUserData();
            }
        } else {
            return $this->usr->listAllUser();
        }
    }

    // Delete data
    private function methodDelete(){

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
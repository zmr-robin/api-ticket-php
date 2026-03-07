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
                break;
            case "PUT":
                break;
            case "DELETE":
                break;
            default:
                break; // ! TODO ERROR
        }

    }

    // Create data
    private function methodPost(){

    }

    /* =================
    
    GET Data 
        List all users:  /users/
    
    By user id
        All data:       /users/data/{id}
        Role of user:   /users/role/{id}
        Email of user:  /users/email/{id}
        
      =================*/
    private function methodGet(){
        if(isset($this->request[1])){
            switch($this->request[1]){
                // Get (all) data for specific user
                case "data":
                    break;
                // Get role of user
                case "role":
                    break;
                // Get email of user
                case "email":
                    break;
            }
        } else {
            return $this->usr->listAllUser();
        }
    }

    // Delete data
    private function methodDelete(){

    }

    // Update data
    private function methodPut(){

    }


}
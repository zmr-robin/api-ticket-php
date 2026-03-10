<?php

namespace App\Controllers;
use App\Services\RoleService;
use App\Exceptions\Exceptions;

class RoleController{

    public $data;
    private $request;
    private $method;
    private $role;

    function __construct($request, $method){
        
        $this->request = $request;
        $this->role = new RoleService($request);
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
    
    GET 
    /roles/:            Get all roles
    /roles/{id}/:       Get role {id} data

        
      =================*/
    private function methodGet(){
        if (isset($this->request[1])) {
            return $this->role->getData();
        } else {
            return $this->role->get();
        }
    }

    /* =================
    
    POST 
    /roles/             Create a role
        
      =================*/
    private function methodPost(){
        return $this->role->create();
    }

    /* =================
    
    PUT 
    /roles/{id}/level     Change role TrustLevel
    /roles/{id}/name      Change role name
      =================*/
    private function methodPut(){
        if(isset($this->request[1]) && isset($this->request[2])){
            switch($this->request[2]){
                case "level":
                    return $this->role->editTrust();
                case "name":
                    return $this->role->editName();
                default:
                    Exceptions::notFound();
            }
        } else {
            Exceptions::notFound();
        }
    }

    /* =================
    
    DELETE 
    / roles/{id}          Delete a role {id}
        
      =================*/
    private function methodDelete(){
        return $this->role->delete();
    }

}

<?php

namespace App\Controllers;

class TicketController {
    public $data;
    private $request;
    private $method;

    function __construct($request, $method){
        
        $this->request = $request;
        $this->method = $method;
        
        switch($this->method){
            case "GET":
                $this->data = $this->methodGet();
                break;
            case "POST":
                $this->data = $this->methodPost();
                break;
            case "PUT":
                //$this->data = $this->methodPut();
                break;
            case "DELETE":
                break;
            default:
                break; // ! TODO ERROR
        }

    }

    
    /* =================
    
    GET 
        List all tickets:  /ticket/
    
    Filter
        Role:       /ticket/role/{RoleID}
        Tag:        /ticket/tag/{Tag}
        
    By ticket id
        Data:       /ticket/{TicketID}
        Messages:   /ticket/{TicketID}/messages
        
      =================*/
    private function methodGet() {
        if(isset($this->request[1])){
            switch($this->request[1]){
                // Get (all) data for specific user
                case "create":
                    return $this->usr->createUser();
                case "invite":
                    $this->usr->inviteUser();
                // Get role of user
                case "role":
                    break;
                // Get email of user
                case "email":
                    break;
                // Return all tickets
                default:
                    break;
            }
        }
    }

    private function methodPost(){

    }

}
<?php

namespace App\Controllers;
use App\Services\TicketService;
use App\Exceptions\Exceptions;

class TicketController {
    public $data;
    private $request;
    private $method;
    private $ticket;

    function __construct($request, $method){
        
        $this->request = $request;
        $this->method = $method;
        $this->ticket = new TicketService($request);

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
        !List all tickets:  /tickets/
    
    By ticket id
        !Data:       /tickets/{TicketID}
        !Email:      /tickets/{TicketID}/email
        !Tag:        /tickets/{TicketID}/tags
        !Messages:   /tickets/{TicketID}/messages
        
      =================*/
    private function methodGet() {
        if(isset($this->request[1])){
            if(isset($this->request[2])){
                switch($this->request[2]){
                    case "email":
                        return $this->ticket->getEmail();
                    case "tags":
                        return $this->ticket->getTags();
                    case "messages":
                        return $this->ticket->getMessages();
                    default:
                        Exceptions::notFound();
                }
            } else {
                return $this->ticket->getData();
            }
        } else {
            return $this->ticket->get();
        }
    }


    /* =================
    
    Create 
        !New ticket:     /tickets/
        
      =================*/

    private function methodPost(){
        return $this->ticket->create();
    }


    /* =================
    
    Update 
        !Archive Ticket:  /tickets/{id}/archive

      =================*/

    private function methodPut(){
        if(isset($this->request[1]) && isset($this->request[2])){
            if ($this->request[2] == "archive"){
                return $this->ticket->archive();
            } else {
                Exceptions::badRequest();
            }
        } else {
            Exceptions::notFound();
        }
    }

    
    /* =================
    
    Delete 
        !Ticket:            /tickets/{id}  

      =================*/
    private function methodDelete(){
        if (isset($this->request[1])){
            return $this->ticket->delete();
        } else {
            Exceptions::badRequest();
        }
    }

}
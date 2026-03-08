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
    
    Filter
        !Tag:        /tickets/tag/{Tag}
        !Archived:   /tickets/archive/
        
    By ticket id
        !Data:       /tickets/{TicketID}
        !Messages:   /tickets/{TicketID}/messages
        !Archived:   /tickets/{TicketID}/archive
        
      =================*/
    private function methodGet() {
        if(isset($this->request[1])){
            switch($this->request[1]){
                // Get tickets by tag
                case "tag":
                    return $this->ticket->getByTag();
                case "archive":
                    return $this->ticket->getArchive();
                default:
                    if (isset($this->request[2])){
                        switch($this->request[2]){
                            case "messages":
                                return $this->ticket->getMessages();
                            case "archive":
                                return $this->ticket->getArchiveData();
                        }
                    } else {
                        return $this->ticket->getData();
                    }
            }
        } else {
            return $this->ticket->get();
        }
    }


    /* =================
    
    Create 
        !New ticket:     /tickets/

    Add to ticket
        !New Message:    /tickets/{id}/message
        !New Tag:        /tickets/{id}/tag
        
      =================*/

    private function methodPost(){
        if(isset($this->request[1])){
            if(isset($this->request[2])){
                switch($this->request[2]){
                    case "message":
                        return $this->ticket->message();
                    case "tag":
                        return $this->ticket->addTag();
                }
            } else {
                Exceptions::notFound();
            }
        } else {
            return $this->ticket->create();
        }
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
        !Ticket Tag:        /tickets/{id}/tag
        !Ticket:            /tickets/{id}  

      =================*/
    private function methodDelete(){
        if(isset($this->request[1]) && isset($this->request[2])){
            return $this->ticket->detachTag();
        } else if (isset($this->request[1])){
            return $this->ticket->delete();
        } else {
            Exceptions::notFound();
        }
    }

}
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
        !List all tickets:  /ticket/
    
    Filter
        !Tag:        /ticket/tag/{Tag}
        !Archived:   /ticket/archive/
        
    By ticket id
        !Data:       /ticket/{TicketID}
        !Messages:   /ticket/{TicketID}/messages
        !Archived:   /ticket/{TicketID}/archive
        
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
        !New ticket:     /ticket/

    Add to ticket
        !New Message:    /ticket/{id}/message
        !New Tag:        /ticket/{id}/tag
        
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
        !Archive Ticket:  /ticket/{id}/archive

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
        !Ticket Tag:        /ticket/{id}/tag
        !Ticket:            /ticket/{id}  

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
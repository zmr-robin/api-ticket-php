<?php

namespace App\Controllers;

use App\Exceptions\Exceptions;
use App\Services\EmailService;

class EmailController{

    public $data;
    private $request;
    private $method;
    private $email;

    function __construct($request, $method){
        
        $this->request = $request;
        $this->email = new EmailService($this->request);
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
    ! /emails/                      Get all email adresses
    ! /emails/{id}                  Get data of email {id}
    ! /emails/{id}/whitelist        Check if email {id} is whitelisted
    ! /emails/{id}/blacklist        Check if email {id} is blacklisted
        
      =================*/
    private function methodGet(){
        if(isset($this->request[1])){
            if(isset($this->request[2])){
                switch($this->request[2]){
                    case "whitelist":
                        return $this->email->getWhitelist();
                    case "blacklist":
                        return $this->email->getBlacklist();
                    default;
                        Exceptions::notFound();
                }
            } else {
                return $this->email->getData();
            }
        } else {
            return $this->email->get();
        }
    }    
    /* =================
    
    POST 
    ! /emails/                          Create email
    ! /emails/{id}/whitelist            Whitelist email {id}
    ! /emails/{id}/blacklist            Blacklist email {id}
    ! /emails/{id}/update/{TicketID}    Send update about ticket {TicketID} to email {id}  
        
      =================*/

    private function methodPost(){
        if (isset($this->request[1]) && isset($this->request[2])){
            switch ($this->request[2]) {
                case "whitelist":
                    return $this->email->whitelist();
                case "blacklist":
                    return $this->email->blacklist();
                case "update":
                    if(isset($this->request[3])){
                        return $this->email->update();
                    } else {
                        Exceptions::badRequest();
                    }
                default:
                    Exceptions::notFound();
            }
        } else {
            return $this->email->create();
        }
    }    

    /* =================
    
    PUT 
    ! /emails/{id}                  Change email address for email {id}

        
      =================*/
    private function methodPut(){
        if(isset($this->request[1]) && count($this->request) <= 2){
            return $this->email->edit();
        } else {
            Exceptions::badRequest();
        }
    }    

    /* =================
    
    DELETE 
    ! /emails/{id}                      Delete email
    ! /emails/{id}/whitelist            Remove a email {id} from whitelist 
    ! /emails/{id}/blacklist            Remove a email {id} from blacklist
        
      =================*/
    private function methodDelete(){
        if (isset($this->request[1]) && isset($this->request[2])){
            switch ($this->request[2]) {
                case "whitelist":
                    return $this->email->removeWhitelist();
                case "blacklist":
                    return $this->email->removeBlacklist();
                default:
                    Exceptions::notFound();
            }
        } else if (isset($this->request[1])) {
            return $this->email->delete();
        }
    }    
}
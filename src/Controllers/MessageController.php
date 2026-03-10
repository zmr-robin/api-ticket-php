<?php

namespace App\Controllers;
use App\Services\MessageService;
use App\Exceptions\Exceptions;

class MessageController{

    public $data;
    private $request;
    private $method;
    private $message;

    function __construct($request, $method){
        
        $this->request = $request;
        $this->message = new MessageService($this->request);
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
        !All messages:              /messages/
        !Message Data:              /messages/{ID}/data

      =================*/

    private function methodGet(){
        if(isset($this->request[1]) && isset($this->request[2])){
            switch($this->request[2]){
                case "data":
                    return $this->message->getData();
                case "ticket":
                    return $this->message->getTicket();
            }
        } else {
            return $this->message->get();
        }
    }

    /* =================
    
    Post 
        !New message:               /messages/
        
      =================*/

    private function methodPost(){
        if (isset($this->request[1])){
            return $this->message->send();
        } else {
            Exceptions::notFound();
        }
    }

    /* =================
    
    Update 
        !Edit Message:               /messages/{MessageID}/
        
      =================*/

    private function methodPut(){
        if (isset($this->request[1]) && count($this->request) = 2){
            return $this->message->edit();
        } else {
            Exceptions::notFound();
        }
    }

    /* =================
    
    Delete 
        !Delete Message:            /messages/{MessageID}/
      =================*/

    private function methodDelete(){
        if (isset($this->request[1]) && count($this->request) = 2){
            return $this->message->delete();
        } else {
            Exceptions::notFound();
        }
    }



}
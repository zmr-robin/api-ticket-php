<?php

namespace App\Controllers;

use App\Services\ArchiveService;
use App\Exceptions\Exceptions;

class ArchiveController{

    public $data;
    private $request;
    private $method;
    private $archive;

    function __construct($request, $method){
        
        $this->request = $request;
        $this->archive = new ArchiveService($this->request);
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
    ! /archive/             Get all archives
    ! /archive/{id}         Get data of archive {id}

      =================*/

    private function methodGet(){
        if (isset($this->request[1]) && count($this->request) == 2){
            return $this->archive->getData();
        } else {
            return $this->archive->get();
        }
    }

    /* =================
    
    POST 
    ! /archive/{id}             Archive a ticket -> ? duplicate /ticket/{id}/archive

      =================*/

    private function methodPost(){
        if(isset($this->request[1])){
            return $this->archive->create();
        } else {
            Exceptions::badRequest();
        }
    }

    /* =================
    
    PUT 
    ! /archive/{id}/comment      Update a archive comment

      =================*/

    private function methodPut(){
        if (isset($this->request[1]) && isset($this->request[2]) && $this->request[2] == "comment"){
            return $this->archive->update();
        } else {
            Exceptions::badRequest();
        }
    }

    /* =================
    
    DELETE 
    ! /archive/{id}             Delete archive 

      =================*/

    private function methodDelete(){
        if(isset($this->request[1])){
            return $this->archive->delete();
        } else {
            Exceptions::badRequest();
        }
    }

}
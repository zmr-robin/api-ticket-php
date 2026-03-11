<?php 

namespace App\Controllers;

use App\Exceptions\Exceptions;
use App\Services\TagService;

class TagController {

    public $data;
    private $request;
    private $method;
    private $tag;

    function __construct($request, $method){
        
        $this->request = $request;
        $this->tag = new TagService($this->request);
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
    ! /tags/              Get all tags
    ! /tags/{id}          Get data from tag {id}          

      =================*/
    private function methodGet(){
        if(isset($this->request[1]) && count($this->request) == 2){
            return $this->tag->getData();
        } else if(count($this->request) == 1) {
            return $this->tag->get();
        } else {
            Exceptions::badRequest();
        }
    }

    /* =================
    
    POST 
    ! /tags/              Create new tag

      =================*/
    private function methodPost(){
        if(count($this->request) == 1){
            return $this->tag->create();
        }
    }

    /* =================
    
    PUT 
    ! /tags/{id}/role               Change role relation to tag {id}
    ! /tags/{id}/description        Change description of tag {id}
    ! /tags/{id}/name               Change name of tag {id}

      =================*/
    private function methodPut(){
        if(isset($this->request[1]) && isset($this->request[2])){
            switch($this->request[2]){
                case "role":
                    return $this->tag->changeRole();
                case "description": 
                    return $this->tag->changeDescription();
                case "name":
                    return $this->tag->changeName();
                default:
                    Exceptions::badRequest();
            }
        } else {
            Exceptions::badRequest();
        }
    }

    /* =================
    
    DELETE 
    ! /tags/{id}/                   Delete tag {id}

      =================*/
    private function methodDelete(){
        if(isset($this->request[1]) && count($this->request) == 2){
            return $this->tag->delete();
        } else {
            Exceptions::badRequest();
        }
    }

}
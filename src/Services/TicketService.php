<?php

namespace App\Services;

use App\Database\Database;

class TicketService {

    private $request;
    private $data;

    function __construct($request){
        $this->request = $request;
    } 

    //* Get all tickets
    public function get(){
        // Get all tickets with state = 1 (open tickets)
        $stmt = Database::$conn->prepare("SELECT * FROM ticket WHERE state = 1;");
        $stmt->execute();
        $result = $stmt->fetchAll();
        $data = [];
        foreach($result as $item){
            array_push($data, [
                "ID" => $item["ID"],
                "EmailID" => $item["EmailID"],
                "Subject" => $item["Subject"],
                "Date" => $item["Date"]
            ]);
        }
        return $data;
    } 

    //* Get all archived tickets
    public function getArchive(){

    }

    //* Get archived ticket data
    public function getArchiveData(){

    }

    //* Get ticket data
    public function getData(){

    }

    //* Get ticket messages
    public function getMessages(){

    }

    //* Get tickets with tag xyz
    public function getByTag(){

    }

    // * Create ticket
    public function create(){

    }

    //* Add new message to ticket
    public function message(){

    }

    //* Add tag to ticket
    public function addTag(){

    }

    //* Remove tag from ticket
    public function detachTag(){

    } 

    //* Archive ticket
    public function archive(){

    }

    //* Delete ticket
    public function delete(){

    }

}
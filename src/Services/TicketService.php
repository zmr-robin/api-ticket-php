<?php

namespace App\Services;

use App\Database\Database;
use App\Exceptions\Exceptions;
use Exception;

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

    //* Get ticket data
    public function getData(){
        $stmt = Database::$conn->prepare("SELECT * FROM ticket WHERE ID = ?;");
        $stmt->execute([$this->request[1]]);
        $result = $stmt->fetch();
        if ($result !== false ){
            return [
                "ID" => $result["ID"],
                "EmailID" => $result["EmailID"],
                "Subject" => $result["Subject"],
                "Date" => $result["Date"]
            ];
        } else {
            Exceptions::notFound("Ticket not found!");
        }
    }

    //* Get email of ticket {id}
    public function getEmail(){
        $emailID = $this->getData();
        $stmt = Database::$conn->prepare("SELECT * FROM email WHERE ID = ?;");
        $stmt->execute([$emailID["EmailID"]]);
        $result = $stmt->fetch();
        if( $result !== false ){
            return [
                "Email" => $result["Email"] 
            ];
        } else {
            Exceptions::notFound("Couldnt find email!");
        }
    }

    //* Get tags of ticket {id}
    public function getTags(){
        $stmt = Database::$conn->prepare("SELECT * FROM tickettag WHERE TicketID = ?");
        $stmt->execute([$this->request[1]]);
        $result = $stmt->fetchAll();
        $data = [];
        foreach($result as $item){
            $stmt = Database::$conn->prepare("SELECT * FROM tag WHERE ID = ?;");
            $stmt->execute([$item["TagID"]]);
            $resultTag = $stmt->fetch();
            if($resultTag !== false){
                array_push($data, [
                    "TagID" => $resultTag["ID"],
                    "Name" => $resultTag["Name"],
                    "Description" => $resultTag["Description"],
                    "Role" => $resultTag["RoleID"],
                    "Date" => $resultTag["Date"]
                ]);
            }
        }
        return $data;
    }

    //* Get ticket messages of ticket {id}
    public function getMessages(){
        $stmt = Database::$conn->prepare("SELECT * FROM messages WHERE TicketID = ?;");
        $stmt->execute([$this->request[1]]);
        $result = $stmt->fetchAll();
        $data = [];
        foreach($result as $item){
            array_push($data, [
                "MessageID" => $item["ID"],
                "EmailID" => $item["EmailID"],
                "TicketID" => $item["TicketID"],
                "Content" => $item["Content"],
                "Date" => $item["Date"]
            ]);
        } 
        return $data;
    }

    // * Create ticket
    public function create(){
        
        $rawBody = file_get_contents("php://input");
        $data = json_decode($rawBody, true); 

        if(isset($data["EmailID"]) && isset($data["Subject"])){
            $state = (isset($data["State"])) ? $data["State"] : 1; // If state is set in json body, change state -> default 1 = open
            $stmt = Database::$conn->prepare("INSERT INTO ticket (EmailID, Subject, state) VALUES (?,?,?);");
            $stmt->execute([$data["EmailID"], $data["Subject"], $state]);
        } else {
            Exceptions::badRequest();
        }

    }

    //* Archive ticket
    public function archive(){
        $stmt = Database::$conn->prepare("SELECT * FROM ticket WHERE ID = ?;");
        $stmt->execute([$this->request[1]]);
        $result = $stmt->fetch();
        if($result !== false){
            $stmt = Database::$conn->prepare("UPDATE ticket SET State = 0 WHERE ID = ?;");
            $stmt->execute([$this->request[1]]);
        } else {
            Exceptions::notFound("Ticket not found!");
        }
    }

    //* Delete ticket
    public function delete(){
        $stmt = Database::$conn->prepare("SELECT * FROM ticket WHERE ID = ?;");
        $stmt->execute([$this->request[1]]);
        $result = $stmt->fetch();
        if($result !== false){
            $stmt = Database::$conn->prepare("DELETE FROM ticket WHERE ID = ?");
            $stmt->execute([$this->request[1]]);
            $stmt = Database::$conn->prepare("DELETE FROM tickettag WHERE TicketID = ?");
            $stmt->execute([$this->request[1]]);
        } else {
            Exceptions::notFound("Ticket not found!");
        }
    }

}
<?php

namespace App\Services;

use App\Database\Database;
use App\Exceptions\Exceptions;

class EmailService {

    private $request;

    function __construct($request){
        $this->request = $request;
    }

    //* Get all emails
    public function get(){
        $stmt = Database::$conn->prepare("SELECT * FROM email;");
        $stmt->execute();
        $result = $stmt->fetchAll();
        $data = [];
        foreach($result as $item){
            array_push($data, [
                "ID" => $item["ID"],
                "Email" => $item["Email"]
            ]);
        }
        return $data;
    }

    //* Get data of email {id}
    public function getData(){
        $stmt = Database::$conn->prepare("SELECT * FROM email WHERE ID = ?;");
        $stmt->execute([$this->request[1]]);
        $result = $stmt->fetch();
        if($result == false){
            Exceptions::notFound("User not found!");
        } else {
            return [
                "ID" => $result["ID"],
                "Email" => $result["Email"]
            ];
        }
    }

    //* Check if email {id} is whitelisted
    public function getWhitelist(){
        $stmt = Database::$conn->prepare("SELECT * FROM whitelist WHERE EmailID = ?;");
        $stmt->execute([$this->request[1]]);
        $result = $stmt->fetch();
        if ($result !== false ){
            return ["Whitelist" => 1]; // 1 = true
        } else {
            return ["Whitelist" => 0]; // 0 = false
        }
    }

    //* Check if email {id} is blacklisted
    public function getBlacklist(){
        $stmt = Database::$conn->prepare("SELECT * FROM blacklist WHERE EmailID = ?;");
        $stmt->execute([$this->request[1]]);
        $result = $stmt->fetch();
        if ($result !== false ){
            return ["Blacklist" => 1]; // 1 = true
        } else {
            return ["Blacklist" => 0]; // 0 = false
        }
    }

    //* Create a email
    public function create(){

        $rawBody = file_get_contents("php://input");
        $data = json_decode($rawBody, true);

        if (isset($data["Email"])) {
            $stmt = Database::$conn->prepare("SELECT * FROM email WHERE Email = ?;");
            $stmt->execute([$data["Email"]]);
            $result = $stmt->fetch();
            if($result == false){
                $stmt = Database::$conn->prepare("INSERT INTO email (Email) VALUES (?);");
                $stmt->execute([$data["Email"]]);
            } else {
                Exceptions::conflict();
            }
        } else {
            Exceptions::badRequest();
        }

    }

    //* Whitelist a email
    public function whitelist(){
        // ! TODO ADD SOURCE
        $stmt = Database::$conn->prepare("SELECT * FROM whitelist WHERE EmailID = ?;");
        $stmt->execute([$this->request[1]]);
        $result = $stmt->fetch();
        if($result == false){
            $stmt = Database::$conn->prepare("INSERT INTO whitelist (EmailID, SourceID) VALUES (?, ?);");
            $stmt->execute([$this->request[1], "Dummy"]); // ! TODO ADD SOURCE
        } else {
            Exceptions::conflict();
        }
    }

    //* Blacklist a email
    public function blacklist(){
        $rawBody = file_get_contents("php://input");
        $data = json_decode($rawBody, true);

        if (isset($data["Reason"])) {
            $stmt = Database::$conn->prepare("SELECT * FROM blacklist WHERE EmailID = ?;");
            $stmt->execute([$this->request[1]]);
            $result = $stmt->fetch();
            if($result == false){
                $stmt = Database::$conn->prepare("INSERT INTO blacklist (EmailID, Reason) VALUES (?, ?);");
                $stmt->execute([$this->request[1], $data["Reason"]]);
            } else {
                Exceptions::conflict();
            }
        } else {
            Exceptions::badRequest();
        }
    }

    //* Send update
    public function update(){
        //! TODO
    }

    //* Send invite
    public function send(){
        
    }

    //* Change email adress
    public function edit(){
        $rawBody = file_get_contents("php://input");
        $data = json_decode($rawBody, true);      
        if (isset($data["Email"])){
            $stmt = Database::$conn->prepare("SELECT * FROM email WHERE ID = ?;");
            $stmt->execute([$this->request[1]]);
            $result = $stmt->fetch();
            if($result !== false){
                $stmt = Database::$conn->prepare("UPDATE email SET Email = ? WHERE ID = ?;");
                $stmt->execute([$data["Email"], $this->request[1]]);
            } else {
                Exceptions::notFound("Email not found!");
            }
        } else {
            Exceptions::badRequest();
        }
    }

    //* Delete a email
    public function delete(){
        $stmt = Database::$conn->prepare("SELECT * FROM email WHERE ID = ?;");
        $stmt->execute([$this->request[1]]);
        $result = $stmt->fetch();
        if($result !== false){
            $stmt = Database::$conn->prepare("DELETE FROM email WHERE ID = ?;");
            $stmt->execute([$this->request[1]]);
        } else {
            Exceptions::notFound("Email not found!");
        }
    }

    //* Remove email {id} from whitelist
    public function removeWhitelist(){
        $stmt = Database::$conn->prepare("SELECT * FROM whitelist WHERE EmailID = ?;");
        $stmt->execute([$this->request[1]]);
        $result = $stmt->fetch();
        if($result !== false){
            $stmt = Database::$conn->prepare("DELETE FROM whitelist WHERE EmailID = ?;");
            $stmt->execute([$this->request[1]]);
        } else {
            Exceptions::notFound("Email not found!");
        }
    }

    //* Remove email {id} from blacklist
    public function removeBlacklist(){
        $stmt = Database::$conn->prepare("SELECT * FROM blacklist WHERE EmailID = ?;");
        $stmt->execute([$this->request[1]]);
        $result = $stmt->fetch();
        if($result !== false){
            $stmt = Database::$conn->prepare("DELETE FROM blacklist WHERE EmailID = ?;");
            $stmt->execute([$this->request[1]]);
        } else {
            Exceptions::notFound("Email not found!");
        }
    }


}
<?php

namespace App\Services;
use App\Exceptions\Exceptions;
use App\Database\Database;

class TagService {

    private $request;

    function __construct($request){
        $this->request = $request;
    }

    //* Get all tags
    public function get(){
        $stmt = Database::$conn->prepare("SELECT * FROM tag;");
        $stmt->execute();
        $result = $stmt->fetchAll();
        $data = [];
        foreach($result as $item){
            array_push($data, [
                "ID" => $item["ID"],
                "Name" => $item["Name"],
                "Description" => $item["Description"],
                "Date" => $item["Date"],
                "Role" => $item["RoleID"]
            ]);
        }
        return $data;
    }

    //* Get of tag {id}
    public function getData(){
        $stmt = Database::$conn->prepare("SELECT * FROM tag WHERE ID = ?;");
        $stmt->execute([$this->request[1]]);
        $result = $stmt->fetch();
        if($result !== false){
            return [
                "ID" => $result["ID"],
                "Name" => $result["Name"],
                "Description" => $result["Description"],
                "Date" => $result["Date"],
                "Role" => $result["RoleID"]
            ];
        } else {
            Exceptions::notFound("Tag not found!");
        }
    }

    //* Create a new tag
    public function create(){
        $rawBody = file_get_contents("php://input");
        $data = json_decode($rawBody, true);

        if(isset($data["Name"]) && isset($data["Description"]) && isset($data["RoleID"])){
            $stmt = Database::$conn->prepare("SELECT * FROM tag WHERE Name = ?;");
            $stmt->execute([$data["Name"]]);
            $result = $stmt->fetch();
            if($result == false){
                $stmt = Database::$conn->prepare("INSERT INTO tag (Name, Description, RoleID) VALUES (?,?,?);");
                $stmt->execute([$data["Name"], $data["Description"], $data["RoleID"]]);
            } else {
                Exceptions::conflict();
            }
        } else {
            Exceptions::badRequest();
        }
    }

    //* Change tag
    public function changeRole(){
        $rawBody = file_get_contents("php://input");
        $data = json_decode($rawBody, true);

        if (isset($data["RoleID"])) {
            $test = $this->getData();
            $stmt = Database::$conn->prepare("UPDATE tag SET RoleID = ? WHERE ID = ?;");
            $stmt->execute([$data["RoleID"], $this->request[1]]);
        }
    }

    //* Change description of tag {id}
    public function changeDescription(){
        $rawBody = file_get_contents("php://input");
        $data = json_decode($rawBody, true);

        if (isset($data["Description"])) {
            $test = $this->getData();
            $stmt = Database::$conn->prepare("UPDATE tag SET Description = ? WHERE ID = ?;");
            $stmt->execute([$data["Description"], $this->request[1]]);
        }
    }

    //* Change name of tag {id}
    public function changeName(){
        $rawBody = file_get_contents("php://input");
        $data = json_decode($rawBody, true);

        if (isset($data["Name"])) {
            
            $stmt = Database::$conn->prepare("SELECT * FROM tag WHERE Name = ?;");
            $stmt->execute([$data["Name"]]);
            $result = $stmt->fetch();

            if ($result == false) {
                $test = $this->getData();
                $stmt = Database::$conn->prepare("UPDATE tag SET Name = ? WHERE ID = ?;");
                $stmt->execute([$data["Name"], $this->request[1]]);
            } else {
                Exceptions::conflict();
            }
        } else {
            Exceptions::badRequest();
        }
    }

    //* Delete a tag {id}
    public function delete(){
        $stmt = Database::$conn->prepare("SELECT * FROM tag WHERE ID = ?;");
        $stmt->execute([$this->request[1]]);
        $result = $stmt->fetch();
        if ($result !== false ){
            $stmt = Database::$conn->prepare("DELETE FROM tag WHERE ID = ?;");
            $stmt->execute([$this->request[1]]);
        } else {
            Exceptions::notFound();
        }
    }

}
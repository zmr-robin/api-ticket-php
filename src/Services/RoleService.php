<?php

namespace App\Services;

use App\Database\Database;
use App\Exceptions\Exceptions;
use App\Http\JsonResponse;

class RoleService {

    private $request;

    function __construct($request){
        $this->request = $request;
    }

    //* Get all
    public function get(){
        $stmt = Database::$conn->prepare("SELECT * FROM role;");
        $stmt->execute();
        $result = $stmt->fetchAll();
        $data = [];
        foreach($result as $item){
            array_push($data, [
                "ID" => $item["ID"],
                "Name" => $item["Name"],
                "TrustLevel" => $item["TrustLevel"]
            ]);
        }
        return $data;
    }

    //* Get data by id
    public function getData(){
        $stmt = Database::$conn->prepare("SELECT * FROM role WHERE ID = ?;");
        $stmt->execute([$this->request[1]]);
        $result = $stmt->fetch();
        if($result == false){
            Exceptions::notFound("User not found");
        } else {
            return [
                "ID" => $result["ID"],
                "Name" => $result["Name"],
                "TrustLevel" => $result["TrustLevel"]
            ];
        }
    }

    //* Create role
    public function create(){
        
        $rawBody = file_get_contents("php://input");
        $data = json_decode($rawBody, true); 

        if(isset($data["Name"]) && isset($data["TrustLevel"])){
            
            $stmt = Database::$conn->prepare("SELECT * FROM role WHERE name = ?;");
            $stmt->execute([$data["Name"]]);
            $result = $stmt->fetch();

            if ($result !== false ){
                Exceptions::conflict();
            }

            $stmt = Database::$conn->prepare("INSERT INTO role (Name, TrustLevel) VALUES (?,?);");
            $stmt->execute([$data["Name"], $data["TrustLevel"]]);

            JsonResponse::$status = 201;
            JsonResponse::$data = ["status" => 201, "content" => "Role with ID ". Database::$conn->lastInsertId() ." created!"];
            JsonResponse::send();
        }
    }

    //* Update role trust level
    public function editTrust(){

        $rawBody = file_get_contents("php://input");
        $data = json_decode($rawBody, true);

        if (isset($data["TrustLevel"])) {
            $stmt = Database::$conn->prepare("SELECT * FROM role WHERE ID = ?;");
            $stmt->execute([$this->request[1]]);
            $result = $stmt->fetch();
            if ($result !== false) {
                $stmt = Database::$conn->prepare("UPDATE role SET TrustLevel = ? WHERE ID = ?;");
                $stmt->execute([$data["TrustLevel"], $this->request[1]]);
            } else {
                Exceptions::notFound();
            }
        } else {
            Exceptions::badRequest();
        }

    }

    //* Update role name
    public function editName(){

        $rawBody = file_get_contents("php://input");
        $data = json_decode($rawBody, true);

        if (isset($data["Name"])) {
            $stmt = Database::$conn->prepare("SELECT * FROM role WHERE ID = ?;");
            $stmt->execute([$this->request[1]]);
            $result = $stmt->fetch();
            if ($result !== false) {
                $stmt = Database::$conn->prepare("UPDATE role SET Name = ? WHERE ID = ?;");
                $stmt->execute([$data["Name"], $this->request[1]]);
            } else {
                Exceptions::notFound();
            }
        } else {
            Exceptions::badRequest();
        }

    }

    //* Delete role
    public function delete(){
        $stmt = Database::$conn->prepare("SELECT * FROM role WHERE ID = ?;");
        $stmt->execute([$this->request[1]]);
        $result = $stmt->fetch();
        if ($result !== false ){
            $stmt = Database::$conn->prepare("DELETE FROM role WHERE ID = ?;");
            $stmt->execute([$this->request[1]]);

            JsonResponse::$status = 200;
            JsonResponse::$data = ["status" => 200, "content" => "Role ID ". $this->request[1] ." deleted!"];
            JsonResponse::send();
        } else {
            Exceptions::notFound();
        }
        
    }

    

}
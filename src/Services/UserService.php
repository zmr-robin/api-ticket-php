<?php

namespace App\Services;

use App\Database\Database;
use App\Http\JsonResponse;
use App\Middleware\Middleware;
use App\Exceptions\Exceptions;
use ReflectionZendExtension;

class UserService
{

    private $request;
    private $data = [];

    function __construct($request)
    {
        $this->request = $request;
    }

    public function listAllUser()
    {

        Middleware::checkIfKeyIsValid();
        Middleware::trustLevel(5);

        $stmt = Database::$conn->prepare("SELECT * FROM supporter");
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach ($result as $item) {
            array_push($this->data, [
                "UserID" => $item["ID"],
                "EmailID" => $item["EmailID"],
                "RoleID" => $item["RoleID"],
                "FirstName" => $item["FirstName"],
                "LastName" => $item["LastName"]
            ]);
        }
        return $this->data;
    }

    public function getUserData()
    {
        Middleware::checkIfKeyIsValid();
        Middleware::trustLevel(5);

        if (isset($this->request[1])) {
            $stmt = Database::$conn->prepare("SELECT * FROM supporter WHERE ID = ?");
            $stmt->execute([$this->request[1]]);
            $result = $stmt->fetch();
            if ($result !== false) {
                $data = [
                    "UserID" => $result["ID"],
                    "EmailID" => $result["EmailID"],
                    "RoleID" => $result["RoleID"],
                    "FirstName" => $result["FirstName"],
                    "LastName" => $result["LastName"]
                ];
                return $data;
            } else {
                Exceptions::notFound("User with ID " . $this->request[2] . " not found!");
            }
        } else {
            Exceptions::notFound("User not found!");
        }
    }

    public function getUserRole(){
        
        Middleware::checkIfKeyIsValid();
        Middleware::trustLevel(5);

        if (isset($this->request[1])){
            $result = $this->getUserData();
            $stmt = Database::$conn->prepare("SELECT * FROM role WHERE ID = ?;");
            $stmt->execute([$result["RoleID"]]);
            $result = $stmt->fetch();
            if ($result !== false){
                $data = [
                    "RoleID" => $result["ID"],
                    "Role" => $result["Name"],
                    "TrustLevel" => $result["TrustLevel"]
                ];
                return $data;
            } else {
                return $data = [
                    "RoleID" => 0,
                    "Role" => "NaN",
                    "TrustLevel" => 0
                ];
            }
        }
    }

    public function setRole() {
        
        $rawBody = file_get_contents("php://input");
        $data = json_decode($rawBody, true); 

        Middleware::checkIfKeyIsValid();
        Middleware::trustLevel(5);

        // ! TODO If user not exist
        if (isset($this->request[1]) && isset($data["Role"])){
            $stmt = Database::$conn->prepare("UPDATE supporter SET RoleID = ? WHERE ID = ?;");
            $stmt->execute([$data["Role"], $this->request[1]]);
            $data = [
                "status" => 200,
                "content" => "Changed role of user " . $this->request[1]
            ];
            return $data;
        } else {
            Exceptions::badRequest();
        }

    }

    public function createUser()
    {
        $rawBody = file_get_contents("php://input");
        $data = json_decode($rawBody, true); 

        if (isset($data["Email"]) && isset($data["Password"]) &&
            isset($data["FirstName"]) && isset($data["LastName"])) {

            $checkWhitelist = $this->checkWhitelist($data["Email"]); 
            if ($checkWhitelist[0] == false){
                Exceptions::forbidden();
            }

            if ($this->userEmailDuplicate($checkWhitelist[0])) {
                Exceptions::conflict("Email already in use!");
            } else {
                // Create user
                $stmt = Database::$conn->prepare("INSERT INTO supporter 
                (EmailID, Password, FirstName, LastName) VALUES (?, ?, ?, ?)");
                $stmt->execute([$checkWhitelist[0] , hash("sha256", $data["Password"]), $data["FirstName"], $data["LastName"]]);
                
                JsonResponse::$status = 201;
                JsonResponse::$data = ["status" => 201, "content" => "User ". Database::$conn->lastInsertId() ." created!"];
                JsonResponse::send();
            }
        } else {
            Exceptions::badRequest("Missing required arguments");
        }
    }

    public function inviteUser(){
        $rawBody = file_get_contents("php://input");
        $data = json_decode($rawBody, true); 

        Middleware::checkIfKeyIsValid();
        Middleware::trustLevel(5);

        if (isset($data["Email"])){
            $emailID = "";
            // Check if email already exist in database
            $stmtEmail = Database::$conn->prepare(query: "SELECT * FROM email WHERE Email = ?;");
            $stmtEmail->execute([$data["Email"]]);
            $result = $stmtEmail->fetch();
            if ($result !== false){
                // Get email id 
                $emailID = $result["ID"];
            } else {
                // Insert email in email
                $stmt = Database::$conn->prepare("INSERT INTO email (Email) VALUES (?);");
                $stmt->execute([$data["Email"]]);
            }
            $emailID = ($emailID != "") ? $emailID : Database::$conn->lastInsertId();
            $stmtWhiteListCheck = Database::$conn->prepare("SELECT * FROM whitelist WHERE EmailID = ?;");
            $stmtWhiteListCheck->execute([$emailID]);
            if (!($result !== false)){
                // Insert email in whitelist
                $stmtWhiteList = Database::$conn->prepare("INSERT INTO whitelist (EmailID) VALUES (?);");
                $stmtWhiteList->execute([$emailID]);
                JsonResponse::$status = 200;
                JsonResponse::$data = ["status" => 200, "content" => "Successfully added " . $data["Email"] . " to whitelist"];
                JsonResponse::send();
            } else {
                Exceptions::conflict();
            }

        }
    }

    private function checkWhitelist($email){
        $stmt = Database::$conn->prepare("SELECT * FROM email WHERE Email = ?;");
        $stmt->execute([$email]);
        $resultEmail = $stmt->fetch();
        if ($resultEmail !== false) {
            $stmt = Database::$conn->prepare("SELECT * FROM whitelist WHERE EmailID = ?;");
            $stmt->execute([$resultEmail["ID"]]);
            $resultWhitelist = $stmt->fetch();
            if ($resultWhitelist !== false){
                return [$resultEmail["ID"]];
            } else {
                return [false];
            }
        } else {
            return [false];
        }
    }

    private function userEmailDuplicate($emailID){
        $stmt = Database::$conn->prepare("SELECT * FROM supporter WHERE EmailID = ?;");
        $stmt->execute([$emailID]);
        $result = $stmt->fetch();
        if($result !== false){
            return true;
        } else {
            return false;
        }
    }

}
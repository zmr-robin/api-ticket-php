<?php

namespace App\Services;

use App\Database\Database;
use App\Http\JsonResponse;
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

    public function listUserData()
    {
        if (isset($this->request[2])) {
            $stmt = Database::$conn->prepare("SELECT * FROM supporter WHERE ID = ?");
            $stmt->execute([$this->request[2]]);
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
                JsonResponse::$status = 404;
                JsonResponse::$data = ["status" => 404, "content" => "User with ID " . $this->request[2] . " not found!"];
                JsonResponse::send();
            }
        } else {
            JsonResponse::$status = 404;
            JsonResponse::$data = ["status" => 404, "content" => "User not found!"];
            JsonResponse::send();
        }
    }

    public function createUser()
    {
        $rawBody = file_get_contents("php://input");
        $data = json_decode($rawBody, true); 

        if (isset($data["Email"]) && isset($data["Password"]) &&
            isset($data["FirstName"]) && isset($data["LastName"])) {

            // Check if email is allready in use
            $stmt = Database::$conn->prepare("SELECT * FROM Email WHERE Email = ?");
            $stmt->execute([$data["Email"]]);
            $result = $stmt->fetch();

            if ($result !== false) {
                JsonResponse::$status = 409;
                JsonResponse::$data = ["status" => 409, "content" => "Email already in use!"];
                JsonResponse::send();
            } else {
                // Create email
                $stmt = Database::$conn->prepare("INSERT INTO Email (email) VALUES (?)");
                $stmt->execute([$data["Email"]]);
                // Create user
                $stmt = Database::$conn->prepare("INSERT INTO supporter 
                (EmailID, Password, FirstName, LastName) VALUES (?, ?, ?, ?)");
                $stmt->execute([Database::$conn->lastInsertId(), hash("sha256", $data["Password"]), $data["FirstName"], $data["LastName"]]);
                
                JsonResponse::$status = 201;
                JsonResponse::$data = ["status" => 201, "content" => "User ". Database::$conn->lastInsertId() ." created!"];
                JsonResponse::send();
            }
        } else {
            JsonResponse::$status = 400;
            JsonResponse::$data = ["status" => 400, "content" => "Missing required fields"];
            JsonResponse::send();
        }
    }

}
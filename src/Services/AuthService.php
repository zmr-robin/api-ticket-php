<?php

namespace App\Services;
use App\Database\Database;
use App\Http\JsonResponse;
use App\Exceptions\Exceptions;

class AuthService
{

    private $request;

    function __construct($request){
        $this->request = $request;
    }

    //* Get all keys
    public function get(){
        $stmt = Database::$conn->prepare("SELECT * FROM api;");
        $stmt->execute();
        $result = $stmt->fetchAll();
        $data = [];
        foreach($result as $item){
            array_push($data, [
                "ID" => $item["ID"],
                "SupporterID" => $item["SupporterID"],
                "Date" => $item["Date"],
                "Duration" => $item["Duration"]
            ]);
        }
        return $data;
    }

    //*  Get data of one key
    public function getData(){
        $stmt = Database::$conn->prepare("SELECT * FROM api WHERE ID = ?;");
        $stmt->execute([hash("sha256" ,$this->request[1])]);
        $result = $stmt->fetch();
        if($result != false){
            return [
                "ID" => $result["ID"],
                "SupporterID" => $result["SupporterID"],
                "Date" => $result["Date"],
                "Duration" => $result["Duration"]
            ];
        } else {
            Exceptions::notFound("Key not found!");
        }
    }

    //* Auth for ID
    public function authByID(){
        
    }

    //* Auth change duration
    public function duration(){
        
    }

    //* Auth delete key
    public function delete(){
        
    }

    //* Auth delete key
    public function deleteUser(){
        
    }

    //* Auth user
    public function auth(){

        $rawBody = file_get_contents("php://input");
        $data = json_decode($rawBody, true);
        if (isset($data["Email"]) && isset($data["Password"])) {
            $stmtEmail = Database::$conn->prepare("SELECT * FROM email WHERE Email = ?;");
            $stmtEmail->execute([$data["Email"]]);
            $resultEmail = $stmtEmail->fetch();
            // If email exist 
            if ($resultEmail !== false) {
                $stmt = Database::$conn->prepare("SELECT * FROM supporter WHERE EmailID = ? AND Password = ?;");
                $stmt->execute([$resultEmail["ID"], hash("sha256", $data["Password"])]);
                $result = $stmt->fetch();
                // If user with same credentials exist
                if ($result !== false) {
                    // Delete old api key of the user
                    $stmtDeleteKey = Database::$conn->prepare("DELETE FROM api WHERE SupporterID = ?;");
                    $stmtDeleteKey->execute([$result["ID"]]);
                    // Create new API-Key
                    $stmtKey = Database::$conn->prepare("INSERT INTO api (ID, SupporterID, Duration) VALUES (?,?,?)");
                    $apiKey = base64_encode(random_bytes(32));
                    $apiKey = str_replace(['+', '/', '='], ['-', '_', ''], $apiKey);
                    $stmtKey->execute([hash("sha256", $apiKey), $result["ID"], 30]);
                    JsonResponse::$status = 200;
                    JsonResponse::$data = ["status" => 200, "content" => $apiKey];
                    JsonResponse::send();
                } else {
                    Exceptions::unauthorized();
                }
            } else {
                Exceptions::unauthorized();
            }
        } else {
            Exceptions::badRequest();
        }

    }

}
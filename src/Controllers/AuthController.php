<?php

namespace App\Controllers;

use App\Database\Database;
use App\Http\JsonResponse;

class AuthController {

    public $data;

    function __construct($request){
        $rawBody = file_get_contents("php://input");
        $data = json_decode($rawBody, true); 
        if (isset($data["Email"]) && isset($data["Password"])) {
            $stmtEmail = Database::$conn->prepare("SELECT * FROM email WHERE Email = ?;");
            $stmtEmail->execute([$data["Email"]]);
            $resultEmail = $stmtEmail->fetch();
            // If email exist 
            if ($resultEmail !== false ){
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
                    JsonResponse::$status = 401;
                    JsonResponse::$data = ["status" => 401, "content" => "Wrong credentials"];
                    JsonResponse::send();
                }
            } else {
                JsonResponse::$status = 401;
                JsonResponse::$data = ["status" => 401, "content" => "Wrong credentials"];
                JsonResponse::send();
            }
        } else {
            JsonResponse::$status = 400;
            JsonResponse::$data = ["status" => 400, "content" => "Missing required arguments"];
            JsonResponse::send();
        }
    }
}
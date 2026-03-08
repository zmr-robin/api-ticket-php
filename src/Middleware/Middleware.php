<?php

namespace App\Middleware;

use App\Database\Database;
use App\Http\JsonResponse;

class Middleware
{

    function __construct(){}

    public static function checkIfKeyIsValid($key){
        $stmt = Database::$conn->prepare("SELECT * FROM api WHERE ID = ?");
        $stmt->execute([hash("sha256", $key)]);
        $result = $stmt->fetch();
        if ($result !== false) {
            $time = strtotime($result["Date"]);
            $now = time();
            if (($now - $time) >= $result["Duration"] * 60) {
                JsonResponse::$status = 403;
                JsonResponse::$data = ["status" => 403, "content" => "Forbidden"];
                JsonResponse::send();
            }
        } else {
            JsonResponse::$status = 403;
            JsonResponse::$data = ["status" => 403, "content" => "Forbidden"];
            JsonResponse::send();
        }
    }


    /*=============

    Check if users Trust-Level allows access
    API Key -> Supporter -> Role = TrustLevel
    
    TrustLevel
    0:  View only tickets with equel role
    1:  + View tickets without tag
    2:  + View all tickets
    3:  + Blacklist a email adress
    4:  + Whitelist a email adress
    5:  + Admin 

      ============= */
    public static function trustLevel($key, $requiredLevel)
    {
        // Get supporter id with api key
        $stmtAPI = Database::$conn->prepare("SELECT * FROM api WHERE ID = ?;");
        $stmtAPI->execute([hash("sha256", $key)]);
        $resultAPI = $stmtAPI->fetch();
        if ($resultAPI !== false) {
            // Get supporter role id
            $stmt = Database::$conn->prepare("SELECT * FROM supporter WHERE ID = ?;");
            $stmt->execute([$resultAPI["SupporterID"]]);
            $result = $stmt->fetch();
            if ($resultAPI !== false) {
                // Get trust level of role
                $stmtRole = Database::$conn->prepare("SELECT * FROM role WHERE ID = ?;");
                $stmtRole->execute([$result["RoleID"]]);
                $resultRole = $stmtRole->fetch();
                // If trust level <= required level denial access 
                if (!($resultRole["TrustLevel"] >= $requiredLevel)) {
                    JsonResponse::$status = 403;
                    JsonResponse::$data = ["status" => 403, "content" => "Forbidden"];
                    JsonResponse::send();
                }
            }
        }
    }

    public static function rateLimit($seconds, $limit)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $key = 'ratelimit_' . md5($ip);
        $file = sys_get_temp_dir() . "/$key";

        if (!file_exists($file)) {
            file_put_contents($file, json_encode(["time" => time(), "count" => 1]));
        } else {
            $data = json_decode(file_get_contents($file), true);
            if (time() - $data["time"] < $seconds) {
                if ($data["count"] >= $limit) {
                    JsonResponse::$status = 429;
                    JsonResponse::$data = ["status" => 429, "content" => "Too Many Requests"];
                    JsonResponse::send();
                } else {
                    $data["count"]++;
                    file_put_contents($file, json_encode($data));
                }
            } else {
                // neues Zeitfenster
                file_put_contents($file, json_encode(["time" => time(), "count" => 1]));
            }
        }

    }
}
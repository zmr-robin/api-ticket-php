<?php

namespace App\Middleware;

use App\Http\JsonResponse;

class Middleware
{

    function __construct(){}

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
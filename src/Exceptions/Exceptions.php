<?php

namespace App\Exceptions;
use App\Http\JsonResponse;
class Exceptions 
{
    function __construct(){}

    public static function badRequest ($content = "Bad Request"){
        JsonResponse::$status = 400;
        JsonResponse::$data = ["status" => 400, "content" => "Bad Request"];
        JsonResponse::send();
    }

    public static function unauthorized ($content = "Unauthorized"){
        JsonResponse::$status = 401;
        JsonResponse::$data = ["status" => 401, "content" => "Unauthorized "];
        JsonResponse::send();
    }

    public static function forbidden($content = "Forbidden"){
        JsonResponse::$status = 403;
        JsonResponse::$data = ["status" => 403, "content" => $content];
        JsonResponse::send();
    }

    public static function notFound($content = "Service not found"){
        JsonResponse::$status = 404;
        JsonResponse::$data = ["status" => 404, "content" => $content];
        JsonResponse::send();
    }

    public static function conflict($content = "Conflict"){
        JsonResponse::$status = 409;
        JsonResponse::$data = ["status" => 409, "content" => $content];
        JsonResponse::send();
    }

    public static function toManyRequests($content = "Too Many Requests"){
        JsonResponse::$status = 429;
        JsonResponse::$data = ["status" => 429, "content" => $content];
        JsonResponse::send();
    }

    public static function serviceUnavailable($content = "Service Unavailable"){
        JsonResponse::$status = 503;
        JsonResponse::$data = ["status" => 429, "content" => $content];
        JsonResponse::send();
    }

    

}
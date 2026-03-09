<?php

// Require Composer autoload
require_once __DIR__ . "/../vendor/autoload.php";

use App\Database\Database;
use App\Routes\Router ;
use App\Http\JsonResponse;
use App\Middleware\Middleware;

// Load environment variables  
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . "/../config");
$dotenv->load();

// Ratelimit
Middleware::rateLimit(60,25);
Middleware::$header = getallheaders();

// Connect to DB
Database::connectDatabase($_ENV["DB"], $_ENV["DB_TABLE"], 
$_ENV["DB_USER"], $_ENV["DB_PASS"]);

// If request -> use Routing Class, else return empty string. 
if (isset($_GET["url"])) {
    $result = new Router($_GET["url"], $_SERVER['REQUEST_METHOD']);
    $data = $result->data ?? [];
    JsonResponse::$data = is_array($data) ? $data : ["content" => $data];
} else {
    JsonResponse::$data = ["message" => "Welcome to the php-ticket api!"];
}

JsonResponse::send();
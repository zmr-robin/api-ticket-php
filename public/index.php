<?php

// Require Composer autoload
require_once __DIR__ . "/../vendor/autoload.php";

use App\Database\Database;
use App\Routes\Router ;
use App\Http\JsonResponse;

// Load environment variables  
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

// Connect to DB
Database::connectDatabase($_ENV["DB"], $_ENV["DB_TABLE"], 
$_ENV["DB_USER"], $_ENV["DB_PASS"]);

// If request -> use Routing Class, else return empty string. 
$result = (isset($_GET["url"])) 
        ? new Router($_GET["url"], $_SERVER['REQUEST_METHOD']) 
        : [""];
        
// Return Json
$return = new JsonResponse($result->data);
$return->send();


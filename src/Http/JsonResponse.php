<?php

namespace App\Http;

use App\Database\Database;

class JsonResponse {

    static public int $status = 200;
    static public array $data = [];
    
    public function __construct() {}

    static public function send(): void {
        http_response_code(self::$status);
        header('Content-Type: application/json');
        echo json_encode(self::$data);
        Database::closeDBConnection();
        exit;
    }
}
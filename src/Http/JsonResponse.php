<?php

namespace App\Http;

class JsonResponse {
    public function __construct(
        private mixed $data,
        private int   $status = 200,
    ) {}

    public function send(): void {
        http_response_code($this->status);
        header('Content-Type: application/json');
        echo json_encode($this->data);
        exit;
    }
}
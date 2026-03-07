<?php

namespace App\Services;

use App\Database\Database;

class UserService{

    private $request;
    private $data = [];

    function __construct($request){
        $this->request = $request;
    }

    public function listAllUser(){
        $stmt = Database::$conn->prepare("SELECT * FROM supporter");
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach ($result as $item){
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
}
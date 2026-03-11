<?php

namespace App\Services;

use App\Database\Database;
use App\Exceptions\Exceptions;

class ArchiveService {

    private $request;

    function __construct($request){
        $this->request = $request;
    }

    //* Get all archives 
    public function get(){
        $stmt = Database::$conn->prepare("SELECT * FROM archive;");
        $stmt->execute();
        $result = $stmt->fetchAll();
        $data = [];
        foreach($result as $item){
            array_push($data, [
                "ID" => $item["ID"],
                "SupporterID" => $item["SupporterID"],
                "Comment" => $item["Comment"],
                "Date" => $item["Date"]
            ]);
        }
        return $data;
    }

    //* Get data from archive {id} 
    public function getData(){
        $stmt = Database::$conn->prepare("SELECT * FROM archive WHERE ID = ?;");
        $stmt->execute([$this->request[1]]);
        $result = $stmt->fetch();
        if ($result !== false) {
            return [
                "ID" => $result["ID"],
                "SupporterID" => $result["SupporterID"],
                "Comment" => $result["Comment"],
                "Date" => $result["Date"] 
            ];
        } else {
            Exceptions::notFound("Archive not found!");
        }
    }

    //* Create a archive for ticket {id} 
    public function create(){

        //! TODO auto detect Supporter ID

        $rawBody = file_get_contents("php://input");
        $data = json_decode($rawBody, true);

        if(isset($data["Comment"])){
            $stmt = Database::$conn->prepare("SELECT * FROM archive WHERE ID = ?;");
            $stmt->execute([$this->request[1]]);
            $result = $stmt->fetch();
            if($result == false){
                $stmt = Database::$conn->prepare("INSERT INTO archive (ID, SupporterID, Comment) VALUES (?,?,?);");
                $stmt->execute([$this->request[1], "0", $data["Comment"]]); // ! TODO remove dummy
            } else {
                Exceptions::conflict();
            }
        } else {
            Exceptions::badRequest();
        }
        
    }

    //* Update a archive {id} comment 
    public function update(){

        $rawBody = file_get_contents("php://input");
        $data = json_decode($rawBody, true);

        if (isset($data["Comment"])){
            $test = $this->getData(); // Verify that archive exist
            $stmt = Database::$conn->prepare("UPDATE archive SET Comment = ? WHERE ID = ?;");
            $stmt->execute([$data["Comment"], $this->request[1]]);
        } else {
            Exceptions::badRequest();
        }

    }

    //* Delete archive 
    public function delete(){
        $test = $this->getData();
        $stmt = Database::$conn->prepare("DELETE FROM archive WHERE ID = ?;");
        $stmt->execute([$this->request[1]]);
    }

}
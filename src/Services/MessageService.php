<?php

namespace App\Services;

use App\Middleware\Middleware;
use App\Services\AuthService;
use App\Database\Database;
use App\Exceptions\Exceptions;

class MessageService {

    private $request;

    function __construct($request){
        $this->request = $request;
    }

    //* Get all messages
    public function get(){
        //! TODO allow filtering by TicketID or EmailID
        $stmt = Database::$conn->prepare("SELECT * FROM messages;");
        $stmt->execute();
        $result = $stmt->fetchAll();
        $data = [];
        foreach($result as $item){
            array_push($data, [
                "ID" => $item["ID"],
                "EmailID" => $item["EmailID"],
                "TicketID" => $item["TicketID"],
                "Content" => $item["Content"],
                "Date" => $item["Date"]
            ]);
        }
        return $data;
    }

    //* Get message data
    public function getData(){
        $stmt = Database::$conn->prepare("SELECT * FROM messages WHERE ID = ?;");
        $stmt->execute([$this->request[1]]);
        $result = $stmt->fetch();
        if($result !== false){
            return [
                "ID" => $result["ID"],
                "EmailID" => $result["EmailID"],
                "TicketID" => $result["TicketID"],
                "Content" => $result["Content"],
                "Date" => $result["Date"]
            ];
        } else {
            Exceptions::notFound("Message not found!");
        }
    }

    //* Send message
    public function send(){
        $rawBody = file_get_contents("php://input");
        $data = json_decode($rawBody, true);

        $key = Middleware::getHeaderKey();
        $key = str_replace("Bearer ", "", $key);
        $key = hash("sha256", $key);

        if (isset($data["TicketID"]) && isset($data["Content"])){
            $stmt = Database::$conn->prepare("SELECT * FROM ticket WHERE ID = ?");
            $stmt->execute([$data["TicketID"]]);
            $result = $stmt->fetch();
            if($result == false){
                Exceptions::notFound("Ticket not found!");
            }
            $stmt = Database::$conn->prepare("SELECT * FROM api WHERE ID = ?;");
            $stmt->execute([$key]);
            $result = $stmt->fetch();
            if($result !== false){
                $stmt = Database::$conn->prepare("SELECT * FROM supporter WHERE ID = ?;");
                $stmt->execute([$result["SupporterID"]]);
                $result = $stmt->fetch();
                if($result !== false){
                    $stmt = Database::$conn->prepare("INSERT INTO messages (EmailID, TicketID, Content) VALUES (?, ?, ?);");
                    $stmt->execute([$result["EmailID"], $data["TicketID"], $data["Content"]]);
                } else {
                    Exceptions::forbidden("API Key doesnt match a supporter account!");
                }
            } else {
                Exceptions::forbidden("API Key doesnt match a supporter account!");
            }
        }
    }

    //* Edit message
    public function edit(){
        Exceptions::badRequest("Service coming soon..."); // ! TODO
    }

    //* Delete message
    public function delete(){
        Exceptions::badRequest("Service coming soon..."); // ! TODO
    }

}
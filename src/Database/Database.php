<?php

namespace App\Database;

use App\Exceptions\Exceptions;
use PDO;
use PDOException;

class Database {
    public static $conn;

    function __construct(){}

    public static function connectDatabase($db, $db_table, $db_user, $db_pass){
        try {
            self::$conn = new PDO("mysql:host=$db;dbname=$db_table", $db_user, $db_pass);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            Exceptions::serviceUnavailable();
        }
    }

    public static function closeDBConnection(){
        self::$conn = null;
    }

}
<?php

namespace App\Services;

use mysqli;

class DatabaseInitializeService
{
    public function execute(): mysqli
    {
        $databaseServername = $_ENV['DB_ADDRESS'];
        $databaseUsername = $_ENV['DB_USERNAME'];
        $databasePassword = $_ENV['DB_PASSWORD'];
        $databaseTable = $_ENV['DB_TABLE'];


        $database = new mysqli($databaseServername, $databaseUsername, $databasePassword, $databaseTable);
        if ($database->connect_error) {
            die("Connection failed: " . $database->connect_error);
        }
        return $database;
    }
}
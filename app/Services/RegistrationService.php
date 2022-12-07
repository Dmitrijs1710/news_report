<?php

namespace App\Services;

use App\Database;
use App\Models\User;


class RegistrationService
{
    public function execute(User $user): bool
    {

        if (!empty($_SESSION['error'])){
            return false;
        }
// Create connection
        $database = Database::getConnection();
// Check connection

        $sql = "INSERT INTO users (email, name, password) VALUES (?,?,?)";
        $statement = $database->prepare($sql);
        $password_hash = password_hash($user->getPassword(), PASSWORD_DEFAULT, []);
        $EMail = $user->getEMail();
        $name = $user->getName();
        $statement->bind_param("sss", $EMail, $name, $password_hash);
        if (!$statement->execute()) {
            $_SESSION['error']['email'] = "Email already exits";
            return false;
        } else {
            $_SESSION['id'] = $database->insert_id;
            return true;
        }
    }
}
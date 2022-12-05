<?php

namespace App\Services;

use App\Models\User;

class AuthenticationService
{
    public function execute(UserLoginRequest $user): bool
    {

// Check connection
        $database = (new DatabaseInitializeService())->execute();

        $sql = "SELECT id,email, name, password FROM users WHERE email='" . $user->getEmail() . "'";
        $result = $database->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $userFromDatabase = new User($row['email'], $row['name'], $row['password'], $row['id']);
                if ($user->checkRequest($userFromDatabase)) {
                    $_SESSION['id'] = $userFromDatabase->getId();
                    $database->close();
                    return true;
                }
            }
        }
        unset($_SESSION['id']);
        $database->close();
        return false;


    }
}
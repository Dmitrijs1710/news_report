<?php

namespace App\Services;

use App\Models\User;
use Exception;

class RegistrationService
{
    public function passwordChecker(string $password): bool
    {
        $passwordPattern = '/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/';
        if (!preg_match($passwordPattern, $password)) {
            return false;
        }
        return true;
    }

    public function emailChecker(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    public function nameChecker(string $name): bool
    {
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            return false;
        }
        return true;
    }

    /**
     * @throws Exception
     */
    public function execute(User $user): void
    {
        $password = $user->getPassword();
        if (!$this->passwordChecker($password)) {
            throw new Exception('Invalid password');
        }
        $EMail = $user->getEMail();
        if (!$this->emailChecker($EMail)) {
            throw new Exception("Invalid email");
        }
        $name = $user->getName();
        if (!$this->nameChecker($name)) {
            throw new Exception("Invalid name");
        }


// Create connection
        $database = (new DatabaseInitializeService())->execute();
// Check connection
        if ($database->connect_error) {
            throw new Exception("Can't connect to DB");
        }

        $sql = "INSERT INTO users (email, name, password) VALUES (?,?,?)";
        $statement = $database->prepare($sql);
        $password_hash = password_hash($password, PASSWORD_DEFAULT, []);
        $statement->bind_param("sss", $EMail, $name, $password_hash);
        if (!$statement->execute()) {
            throw new Exception("Email is already registered");
        } else {
            $_SESSION['id'] = $database->insert_id;
        }
        $database->close();
    }
}
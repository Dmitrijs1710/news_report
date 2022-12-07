<?php

namespace App\Services;

use App\Database;

class UserDataUpdateService
{
    public function execute(string $field, string $value, int $id): bool
    {
        $database = Database::getConnection();
        if ($field === 'password') {
            $value = password_hash($value, PASSWORD_DEFAULT);
        }
        $sql = "UPDATE users SET $field='$value' WHERE id='$id'";
        if ($database->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
}
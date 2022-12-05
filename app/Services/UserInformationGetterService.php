<?php

namespace App\Services;

use App\Models\User;

class UserInformationGetterService
{
    public function execute(int $id): ?User
    {
        $database = (new DatabaseInitializeService())->execute();
        $sql = "SELECT id,email, name, password FROM users WHERE id='" . $id . "'";
        $result = $database->query($sql);
        if ($result->num_rows === 1) {
            $data = $result->fetch_assoc();
            return new User($data['email'], $data['name'], null, $data['id']);
        }
        return null;
    }
}
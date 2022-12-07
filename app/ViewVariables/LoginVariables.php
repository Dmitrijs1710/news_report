<?php

namespace App\ViewVariables;

use App\Services\UserInformationGetterService;

class LoginVariables
{

    public function getName(): string
    {
        return 'login';
    }

    public function getValues(): ?array
    {
        if ($_SESSION['id'] !== null) {
            $user = (new UserInformationGetterService())->execute($_SESSION['id']);
            return [
                'id' => $user->getId(),
                'email' => $user->getEMail(),
                'name' => $user->getName(),
            ];
        }
        return null;
    }
}
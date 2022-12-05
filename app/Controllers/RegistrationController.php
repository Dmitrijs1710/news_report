<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\CategoryNavigationService;
use App\Services\RegistrationService;
use App\Services\UserInformationGetterService;
use App\Template;
use Exception;

class RegistrationController
{
    public function index(): Template
    {
        if ($_SESSION['id'] !== null) {
            header("Location: /");
            exit();
        }
        $menu = (new CategoryNavigationService())->getCategoryMenu();
        return new Template('Registration/registration.html', [
            'categories' => $menu,
            'login' => null
        ]);
    }

    public function registrationHandler(): Template
    {
        $menu = (new CategoryNavigationService())->getCategoryMenu();
        if ($_POST['password'] !== $_POST['passwordRepeat']) {
            return new Template('Registration/registration.html', [
                'categories' => $menu,
                'message' => "Passwords don't match",
                'login' => $_SESSION['id'] !== null ? (new UserInformationGetterService())->execute($_SESSION['id']) : null
            ]);
        }
        $user = new User($_POST['email'], $_POST['name'], $_POST['password']);
        try {
            (new RegistrationService())->execute($user);
            return new Template('Registration/successful.html', [
                'categories' => $menu,
                'login' => $_SESSION['id'] !== null ? (new UserInformationGetterService())->execute($_SESSION['id']) : null
            ]);
        } catch (Exception $e) {
            return new Template('Registration/registration.html', [
                'categories' => $menu,
                'message' => $e->getMessage(),
                'login' => $_SESSION['id'] !== null ? (new UserInformationGetterService())->execute($_SESSION['id']) : null
            ]);
        }


    }
}
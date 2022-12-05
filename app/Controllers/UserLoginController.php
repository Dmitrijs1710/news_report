<?php

namespace App\Controllers;

use App\Services\AuthenticationService;
use App\Services\CategoryNavigationService;
use App\Services\UserInformationGetterService;
use App\Services\UserLoginRequest;
use App\Template;

class UserLoginController
{
    public function index(): Template
    {
        if ($_SESSION['id'] !== null) {
            header("Location: /");
            exit();
        }
        $menu = (new CategoryNavigationService())->getCategoryMenu();
        return new Template('Login/login.html', [
            'categories' => $menu,
            'login' => null
        ]);
    }

    public function loginHandler(): Template
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $menu = (new CategoryNavigationService())->getCategoryMenu();
        $response = (new AuthenticationService())->execute(new UserLoginRequest($email, $password));
        if ($response) {
            return new Template('/Login/successful.html', [
                'categories' => $menu,
                'message' => 'Login',
                'login' => $_SESSION['id'] !== null ? (new UserInformationGetterService())->execute($_SESSION['id']) : null
            ]);
        }
        return new Template('/Login/login.html', [
            'categories' => $menu,
            'message' => 'Incorrect email or password',
            'login' => $_SESSION['id'] !== null ? (new UserInformationGetterService())->execute($_SESSION['id']) : null
        ]);

    }

    public function logoutHandler(): Template
    {
        $menu = (new CategoryNavigationService())->getCategoryMenu();
        unset($_SESSION['id']);
        return new Template('/Login/successful.html', [
            'categories' => $menu,
            'message' => 'Logout',
            'login' => $_SESSION['id'] !== null ? (new UserInformationGetterService())->execute($_SESSION['id']) : null
        ]);
    }
}
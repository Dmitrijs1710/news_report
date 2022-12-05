<?php

namespace App\Controllers;

use App\Services\CategoryNavigationService;
use App\Services\UserInformationGetterService;
use App\Template;

class ProfileController
{
    public function index(): Template
    {
        $menu = (new CategoryNavigationService())->getCategoryMenu();
        $id = $_SESSION['id'] ?? null;
        if ($id === null) {
            header("Location: /login");

            exit();
        }
        $user = (new UserInformationGetterService())->execute($id);
        return new Template('/Profile/index.html', [
            'categories' => $menu,
            'user' => $user,
            'login' => $_SESSION['id'] !== null ? (new UserInformationGetterService())->execute($_SESSION['id']) : null
        ]);
    }
}
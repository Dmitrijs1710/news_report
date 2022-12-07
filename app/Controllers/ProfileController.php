<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\UserDataUpdateService;
use App\Template;
use App\Validate;

class ProfileController
{
    public function index(): Template
    {
        $id = $_SESSION['id'] ?? null;
        if ($id === null) {
            header("Location: /login");

            exit();
        }
        return new Template('/Profile/index.html', [
        ]);
    }

    public function updateData(): Redirect
    {
        if ($_POST['name'] != null) {
            Validate::nameChecker($_POST['name']);
            $field = 'name';
        } else if ($_POST['password'] != null) {
            Validate::passwordMatch($_POST['password'], $_POST['passwordRepeat']);
            Validate::passwordChecker($_POST['password']);
            $field = 'password';
        } else {
            Validate::emailChecker($_POST['email']);
            $field = 'email';
        }
        if (empty($_SESSION['error'])) {
            if ((new UserDataUpdateService())->execute($field, $_POST[$field], $_SESSION['id'])) {
                $_SESSION['error'][$field] = 'successfully changed';
            } else {

                $_SESSION['error'][$field] = 'database error';
            }

        }
        return new Redirect('/profile');

    }
}
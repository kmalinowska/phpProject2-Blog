<?php

namespace App\Controllers;
use Core\View;

class AuthController {
    //display the form
    public function create() {
        return View::render(
            template: 'auth/create',
            layout: 'layouts/main'
        );
    }

    //responsible for the form submission
    public function store() {
        var_dump($_POST);
        die('Form sent!');
    }
}
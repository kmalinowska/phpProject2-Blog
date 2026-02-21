<?php

namespace App\Controllers;
use Core\View;
use Core\Router;
use App\Services\Auth;


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
        //To do: verify CSRF token
        $email = $_POST['email'];
        $password = $_POST['password'];

        //Attemp authentication
        if(Auth::attempt($email, $password)) {
            Router::redirect('/');
        }
        
        return View::render(
            template: 'auth/create',
            layout: 'layouts/main',
            data: [
                'error' => 'Invalid credentials'
            ]
        );
    }

    public function destroy() {
        Auth::logout();
        Router::redirect('/login');
    }
}
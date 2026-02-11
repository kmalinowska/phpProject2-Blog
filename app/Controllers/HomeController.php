<?php

namespace App\Controllers;

use Core\View;
use Exception;
use App\Models\User;

class HomeController {
    public function index() {
        User::create([
            'name' => 'Piotr',
            'email' => 'piotr@gmail.com',
            'role' => 'admin',
            'password' => password_hash('xyz123', PASSWORD_DEFAULT)
        ]);
        User::create([
            'name' => 'Janusz',
            'email' => 'janusz@gmail.com',
            'role' => 'admin',
            'password' => password_hash('januszek123', PASSWORD_DEFAULT)
        ]);
        return View::render(
            template: 'home/index', 
            data: ['message' => 'Hello'],
            layout: 'layouts/main'
        );
    }
}
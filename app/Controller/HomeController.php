<?php

namespace App\Controller;

use Core\View;
use Exception;

class HomeController {
    public function index() {
        throw new Exception("This has happened on the web!");
        return View::render(
            template: 'home/index', 
            data: ['message' => 'Hello'],
            layout: 'layouts/main'
        );
    }
}
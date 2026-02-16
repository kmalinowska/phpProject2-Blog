<?php

namespace App\Controllers;

use Core\View;
use App\Models\Post;

class HomeController {
    public function index() {
        $posts = Post::getRecent(5);

        return View::render(
            template: 'home/index', 
            data: [
                'posts' => $posts
            ],
            layout: 'layouts/main'
        );
    }
}
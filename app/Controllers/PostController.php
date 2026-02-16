<?php

namespace App\Controllers;
use app\Models\Post;
use core\Router;

class PostController {
    public function index() {
        return "All posts";
    }

    public function show($id) {
        // 1) Fetch
        $post = Post::find($id);
        // 2) 404
        if(!$post) {
            Router::notFound();
        }
        // 3) Load comments
        // 4) Increment view number
        // 5) Render the blog posts with the comments
    }
}
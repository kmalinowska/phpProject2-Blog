<?php

namespace App\Controllers;
use app\Models\Post;
use app\Models\Comment;
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
        $comments = Comment::forPost($id);
        // 4) Increment view number
        Post::incrementViews($id);
        // 5) Render the blog posts with the comments
    }
}
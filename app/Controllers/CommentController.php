<?php

namespace App\Controllers;
use App\Services\Auth;
use App\Models\Comment;
use Core\Router;


class CommentController {
    public function store($id) {
        $content = $_POST['content'];
        Comment::create([
            'post_id' => $id,
            'user_id' => Auth::user()->id,
            'content' => $content
        ]);

        return Router::redirect("/posts/{$id}#comments");
    }

    
}
<?php

namespace App\Controllers\Admin;

use App\Models\Post;
use App\Services\Auth;
use App\Services\Authorization;
use Core\Router;
use Core\View;

class PostController {
    public function index() {
        // + Pagination
        // + Search
        $search = $_GET['search'] ?? null;
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 10;

        $posts = Post::getRecent(
            limit: $limit,
            page: $page,
            search: $search
        );

        $total = Post::count($search);
        $totalPages = (int) ceil($total / $limit);
       //list all the posts to manage
        return View::render(
           template: 'admin/posts/index',
           data: [
               'posts' => $posts,
               'search' => $search,
               'page' => $page,
               'totalPages' => $totalPages
           ],
           layout: 'layouts/admin'
        );
    }

    public function create() {
        //displays a form
        return View::render(
            template: 'admin/posts/create',
            layout: 'layouts/admin'
        );
    }

    public function store() {
        //handles the form submission
        Authorization::verify('create_post');
        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'content' => trim($_POST['content'] ?? ''),
            'user_id' => Auth::user()->id
        ];
        Post::create($data);
        Router::redirect('/admin/posts');
    }

    public function edit($id) {
        //display an edit form
        $post = Post::find($id);
        if(!$post) {
            Router::notFound();
        }
        Authorization::verify('edit_post', $post);
        return View::render(
            template: 'admin/posts/edit',
            data: ['post' => $post],
            layout: 'layouts/admin'
        );
    }

    public function update($id) {
        //handles the submission of the edit form
        $post = Post::find($id);
        if(!$post) {
            Router::notFound();
        }
        Authorization::verify('edit_post', $post);
        $post->title = $_POST['title'];
        $post->content = $_POST['content'];
        $post->save();
        // + static update ()
        Router::redirect('/admin/posts');
    }

    public function delete($id) {
        //Post: findOrFail($id);
        //return the model or return a 404 not found page if the model wasn't find

        //delete a post
        $post = Post::find($id);
        if(!$post) {
            Router::notFound();
        }
        Authorization::verify('delete_post', $post);
        $post->delete();
        Router::redirect('/admin/posts');
    }
}

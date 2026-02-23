<?php

namespace App\Middlewares;
use Core\Middleware;
use App\Services\Auth;
use Core\View as CoreView; //alias

class View implements Middleware {
    public function handle(callable $next) {
        CoreView::share('user', Auth::user());
        return $next();
    }
}
<?php

namespace App\Middlewares;

use Core\Middleware;
use App\Services\CSRF as ServicesCSRF;
use Core\Router;

class CSRF implements Middleware {
  public function handle(callable $next) {
    if(!ServicesCSRF::verify()) {
        Router::pageExpired();        
    }
    return $next();
    }  
}

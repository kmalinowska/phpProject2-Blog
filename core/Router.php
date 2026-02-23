<?php

namespace Core;

class Router {
    protected array $routes = [];
    protected array $globalMiddleware = [];
    protected array $routeMiddleware = [];

    public function add(string $method, string $uri, string $controller, array $middlewares = []):void {
        $this->routes[] = [
            'method' => strtoupper($method),
            'uri' => $uri,
            'controller' => $controller,
            'middlewares' => $middlewares        
        ];
    }

    //methods allows to add middlewares
    public function addGlobalMiddleware(string $middleware) {
        $this->globalMiddleware[] = $middleware;    
    }
    public function addRouteMiddleware(string $name, string $middleware) {
        $this->routeMiddleware[$name] = $middleware;    
    }

    //error functions:
    public static function notFound(): void {
        http_response_code(404);
        echo View::render('errors/404');
        exit;
    }
    public static function unauthorized(): void {
        http_response_code(401);
        echo View::render('errors/401');
        exit;
    }
    public static function pageExpired(): void {
        http_response_code(419);
        echo View::render('errors/419');
        exit;
    }

    public function dispatch(string $uri, string $method): void {
        $uri = parse_url($uri, PHP_URL_PATH) ?? '/';
        $method = strtoupper($method);
        $route = $this->findRoute($uri, $method);

        if(!$route) {
            static::notFound();
        }

        // auth -> App/Middlewares/AuthMiddleware
        $routeMiddlewares = $route['middlewares'] ?? [];
        $middlewares = [
            ...$this->globalMiddleware,
            ...array_map(
                fn($name) => $this->routeMiddleware[$name], 
                $routeMiddlewares
            )
        ];

        $this->runMiddleware(
            $middlewares, 
            function () use ($route): void{
                [$controller, $action] = explode('@', $route['controller']);
                echo $this->callAction($controller, $action, $route['params']);
            }
        );
    }

    //function run all the middleware
    protected function runMiddleware(array $middlewares, callable $target): mixed {
        $next = $target;
        foreach(array_reverse($middlewares) as $middleware) {
            $next = (new $middleware)->handle($next);
        }
        return $next();
    }

    protected function findRoute(string $uri, string $method): ?array {
        foreach($this->routes as $route) {
            $params = $this->matchRoute($route['uri'], $uri);
            if($params !== null && $route['method'] === $method) {
                return [...$route, 'params' => $params];
            }
        }
        return null;
    }

    protected function matchRoute(string $routeUri, string $requestUri): ?array {
        $routeSegments = array_values(array_filter(explode('/', trim($routeUri, '/'))));
        $requestSegments = array_values(array_filter(explode('/', trim($requestUri, '/'))));

        if(count($routeSegments) !== count($requestSegments)) {
            return null;
        }

        $params = [];

        foreach($routeSegments as $index => $routeSegment) {
            if(str_starts_with($routeSegment, '{') && str_ends_with($routeSegment, '}')) {
                $params[trim($routeSegment, '{}')] = $requestSegments[$index];
            } elseif($routeSegment !== $requestSegments[$index]) {
                return null;
            }
        }
        return $params;
    }

    protected function callAction(string $controller, string $action, array $params): mixed {
        $controllerClass = "App\\Controllers\\$controller";

        if(!class_exists($controllerClass)) {
            $this->notFound();
        }

        if (!method_exists($controllerClass, $action)) {
            $this->notFound();
        }

        return (new $controllerClass)->$action(...$params);
    }

    public static function redirect (string $uri): void {
        header("Location: $uri");
        exit();
    }
}
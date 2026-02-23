<?php

namespace Core;

class Router {
    protected array $routes = [];

    public function add(string $method, string $uri, string $controller):void {
        $this->routes[] = [
            'method' => strtoupper($method),
            'uri' => $uri,
            'controller' => $controller
        ];
    }

    public static function notFound(): void {
        http_response_code(404);
        echo View::render('errors/404');
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

        $parts = explode('@', $route['controller'],2);
        if(count($parts) !==2) {
            throw new \Exception("Invalid controller format");
        }
        [$controller, $action] = $parts;

        echo $this->callAction($controller, $action, $route['params']);
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
<?php
declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

use Core\Router;

$router = new Router();

require_once __DIR__ . '/../routes.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$router->dispatch($uri, $method);

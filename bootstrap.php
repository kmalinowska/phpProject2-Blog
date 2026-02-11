<?php
declare(strict_types = 1);
require_once __DIR__ . '/vendor/autoload.php';

use Core\App;
use Core\Database;
use Core\ErrorHandler;

set_exception_handler([ErrorHandler::class, 'handleException']); //if callback is a method on a class, pass it as an array
set_error_handler([ErrorHandler::class, 'handleError']);
$config = require_once __DIR__ . '/config.php';

App::bind('config', $config);
App::bind('database', new Database($config['database']));
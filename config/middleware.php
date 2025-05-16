<?php

use Core\Middleware\ControllerDispatcherMiddleware;
use Core\Middleware\RouterMiddleware;
use Middlewares\BasePath;
use Middlewares\Whoops;
use Core\Middleware\ErrorMiddleware;
use App\Middleware\RoutingMiddleware;
use Core\Logger;
use Core\Middleware\FallbackHandler;

// ... autres middlewares


$routes = require_once ROOT . '/config/routes.php';


return [
    new ErrorMiddleware(new Logger(logDir: $_ENV['LOG_PATH']), debug: $_ENV['APP_DEBUG'] ),        
    new BasePath(dirname($_SERVER['SCRIPT_NAME'])),
    new RouterMiddleware($routes),
    new ControllerDispatcherMiddleware(),
    new FallbackHandler(), // Obligatoire pour finir la chaîne proprement
];

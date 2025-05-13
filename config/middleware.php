<?php

use Middlewares\Whoops;
use Core\Middleware\ErrorMiddleware;
use App\Middleware\RoutingMiddleware;
use Core\Logger;
use Core\Middleware\FallbackHandler;

// ... autres middlewares




return [
    new ErrorMiddleware(new Logger(logDir: $_ENV['LOG_PATH']), debug: $_ENV['APP_DEBUG'] ),        
    

    new FallbackHandler(), // Obligatoire pour finir la chaîne proprement
];

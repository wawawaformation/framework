<?php
/**
 * Entree de notre application
 */

 use Relay\Relay;
 use Core\Http\Factory\ServerRequestFactory;
 use Psr\Http\Message\ResponseInterface;
 use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;


define('ROOT', dirname(__DIR__));


require ROOT . '/config/bootstrap.php';


$request = ServerRequestFactory::fromGlobals();



// Charge la liste des  middlewares []
$middlewareQueue = require ROOT . '/config/middleware.php';




// Création du dispatcher PSR-15 avec Relay
$relay = new Relay($middlewareQueue);

$response = $relay->handle($request);






$emitter = new SapiEmitter();
$emitter->emit($response);

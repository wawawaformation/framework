<?php

namespace Core\Middleware;

use App\Controller\ErrorController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use GuzzleHttp\Psr7\Response;

class ControllerDispatcherMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $controllerClass = $request->getAttribute('controller');
        $method = $request->getAttribute('method');
        $params = $request->getAttribute('params', []);

        // Si le contrôleur ou la méthode n'existent pas, on passe à ErrorController::notFound()
        if (
            !$controllerClass ||
            !$method ||
            !class_exists($controllerClass) ||
            !method_exists($controllerClass, $method)
        ) {
            return (new ErrorController())->notFound($request);
        }

        $controller = new $controllerClass();

        $response = call_user_func_array([$controller, $method], $params);

        if (!$response instanceof ResponseInterface) {
            return (new ErrorController())->serverError($request);
        }

        return $response;
    }
}

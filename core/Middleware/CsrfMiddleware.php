<?php

namespace Core\Middleware;

use App\Controller\ErrorController;
use Core\Logger;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class CsrfMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Méthodes sensibles
        if (in_array($request->getMethod(), ['POST', 'PUT', 'DELETE'])) {
            $params = $request->getParsedBody();
            $sessionToken = $_SESSION['_csrf_token'] ?? null;
            $formToken = $params['_csrf_token'] ?? null;


            
            if (!$sessionToken || !$formToken || !hash_equals($sessionToken, $formToken)) {
                new Logger($_ENV['LOG-PATH'], $_ENV['APP_DEBUG'])->warning('CSRF token invalide', ['uri' => (string)$request->getUri()]);

               $controller = new \Core\Controller\ErrorController();
               $controller->forbidden($request);

            }
        }

        return $handler->handle($request);
    }
}

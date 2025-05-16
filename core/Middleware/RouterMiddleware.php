<?php

namespace Core\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use GuzzleHttp\Psr7\Response;

class RouterMiddleware implements MiddlewareInterface
{
    protected array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        $method = $request->getMethod();

        foreach ($this->routes as $route) {
            [$httpMethod, $routePath, $handlerInfo] = $route;

            $regex = '#^' . preg_replace('#\{([\w]+)\}#', '(?P<$1>[^/]+)', $routePath) . '$#';

            if ($method === $httpMethod && preg_match($regex, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                return $handler->handle(
                    $request
                        ->withAttribute('controller', $handlerInfo[0])
                        ->withAttribute('method', $handlerInfo[1])
                        ->withAttribute('params', $params)
                );
            }
        }

        // Si aucune route ne correspond, on passe à la suite de la chaîne
        return $handler->handle($request);
    }
}

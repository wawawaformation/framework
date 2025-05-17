<?php

declare(strict_types=1);

namespace Core\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use GuzzleHttp\Psr7\Response;

class FallbackHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        return new Response(
            404,
            ['Content-Type' => 'text/html'],
            '<h1>404 - Page non trouvée</h1>'
        );
    }
}

<?php

declare(strict_types=1);

namespace Core\Controller;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class ErrorController extends AbstractController
{
    public function notFound(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(
            404,
            ['Content-Type' => 'text/html'],
            '<h1>404 - Page non trouvée</h1><p>La page demandée est introuvable.</p>'
        );
    }

    public function forbidden(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(
            403,
            ['Content-Type' => 'text/html'],
            '<h1>403 - Interdit</h1><p> Le serveur a compris la demande, mais refuse de l\'autoriser.</p>'
        );
    }


    public function serverError(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(
            500,
            ['Content-Type' => 'text/html'],
            '<h1>500 - Erreur interne</h1><p>Une erreur s\'est produite.</p>'
        );
    }
}

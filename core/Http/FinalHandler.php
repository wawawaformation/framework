<?php

declare(strict_types=1);

namespace Core\Http;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Gestionnaire final de la pile de middleware.
 *
 * Ce `RequestHandler` est appelé à la fin de la chaîne si aucun autre middleware
 * n’a généré de réponse. Il garantit qu’une réponse est toujours retournée à l’utilisateur.
 *
 * Utile en développement ou comme secours pour éviter les erreurs 500 silencieuses.
 */
class FinalHandler implements RequestHandlerInterface
{
    /**
     * Gère la requête HTTP et retourne une réponse générique.
     *
     * @param ServerRequestInterface $request La requête HTTP PSR-7 entrante.
     * @return ResponseInterface Une réponse simple avec code 200 ou un message de secours.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(
            200,
            ['Content-Type' => 'text/plain'],
            "Hello! Aucun middleware n’a produit de réponse.\n"
        );
    }
}

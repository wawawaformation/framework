<?php

declare(strict_types=1);

namespace Core\Middleware;

use Core\Controller\ErrorController;
use GuzzleHttp\Psr7\Response;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware de gestion des erreurs non interceptées.
 *
 * - Journalise toute exception levée par les middlewares suivants ou le contrôleur.
 * - Génère une réponse HTTP 500 avec un message générique ou détaillé selon l'environnement.
 * - Désactivé automatiquement si `filp/whoops` est actif (en DEV).
 */
class ErrorMiddleware implements MiddlewareInterface
{
    /**
     * Logger PSR-3 pour journaliser les erreurs.
     */
    protected LoggerInterface $logger;

    /**
     * Indique si l'environnement est en développement.
     * Si oui, le middleware n'interceptera pas les erreurs (Whoops s'en charge).
     */
    protected bool $debug;

    public function __construct(LoggerInterface $logger, bool $debug = false)
    {
        $this->logger = $logger;
        $this->debug = $debug;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Si en mode debug (dev), on ne capture pas — Whoops prend le relai
        if ($this->debug) {
            return $handler->handle($request);
        }

        try {
            return $handler->handle($request);
        } catch (\Throwable $e) {
            $this->logger->critical("Exception non interceptée : {$e->getMessage()}");

            return (new ErrorController())->serverError($request);
        }
    }
}

<?php

declare(strict_types=1);

namespace Core\Controller;

use AltoRouter;

/**
 * Classe Router
 *
 * Gère la configuration et la gestion des routes de l'application via AltoRouter.
 * Elle permet d'associer des URLs à des contrôleurs/méthodes, de charger les routes définies
 * dans le fichier `routes.php`, et de gérer la redirection des requêtes.
 */
class Router
{
    /**
     * Instance du routeur AltoRouter.
     *
     * @var AltoRouter
     */
    protected AltoRouter $router;

    /**
     * Initialise le routeur et charge les routes définies dans le projet.
     */
    public function __construct()
    {
        $this->router = new AltoRouter();
        $this->loadRoutes();
    }

    /**
     * Charge les routes depuis le fichier dédié.
     *
     * @return void
     */
    private function loadRoutes(): void
    {
        $router = $this->router;
        require_once ROOT .  '/routes.php';
    }

    /**
     * Traite la requête HTTP en cours et exécute la cible correspondante.
     * En cas d'absence de correspondance, déclenche une erreur 404.
     *
     * @return void
     */
    public function handleRequest(): void
    {
        $match = $this->router->match();

        if (is_array($match) && is_callable($match['target'])) {
            call_user_func_array($match['target'], $match['params']);
        } else {
            $this->handleError();
        }
    }

    /**
     * Gère les erreurs HTTP rencontrées.
     *
     *
     *
     * @return void
     */
    private function handleError(): void
    {
        $module = str_contains($_SERVER['REQUEST_URI'], 'admin') ? 'back' : 'front';
        (new \Core\Controller\ErrorController())->httpError($module, 404);
    }
}

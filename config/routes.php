<?php

/**
 * Définition des routes de l’application.
 *
 * Chaque route est un tableau composé de :
 * - La méthode HTTP (GET, POST, etc.)
 * - Le chemin (éventuellement avec paramètres dynamiques comme /blog/{id})
 * - Un tableau contenant :
 *     - Le FQCN du contrôleur
 *     - Le nom de la méthode à appeler
 */

return [
    // Page d'accueil
    [
        'GET',
        '/',
        [\App\Controller\HomeController::class, 'index']
    ],

   
];

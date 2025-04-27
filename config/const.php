<?php

declare(strict_types=1);

/**
 * Constantes d'environnement
 */

define('ROOT', dirname(__DIR__));
define('DEV', true);
define('LOG', ROOT . '/logs');

// Définir URL uniquement si REQUEST_SCHEME et HTTP_HOST existent
if (isset($_SERVER['REQUEST_SCHEME'], $_SERVER['HTTP_HOST'])) {
    define('URL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']);
} else {
    define('URL', 'http://localhost'); // Valeur par défaut pour le mode CLI
}

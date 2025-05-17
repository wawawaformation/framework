<?php

/**
 * Chargement du .env + config globale
 */

declare(strict_types=1);

use Dotenv\Dotenv;

if (!defined("ROOT")) {
    define('ROOT', dirname(__DIR__));
}

require_once ROOT . '/vendor/autoload.php';


$dotenv = Dotenv::createImmutable(ROOT);
$dotenv->safeload();

define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');
define('APP_DEBUG', ($_ENV['APP_DEBUG'] ?? 'false') === 'true');



// Configuration du rapport d'erreurs selon l'environnement
ini_set('display_errors', APP_DEBUG ? '1' : '0');
ini_set('display_startup_errors', APP_DEBUG ? '1' : '0');
error_reporting(APP_DEBUG ? \E_ALL : 0);


if ($_ENV['APP_ENV'] === 'dev') {
    (new \Whoops\Run())
        ->pushHandler(new \Whoops\Handler\PrettyPageHandler())
        ->register();
}

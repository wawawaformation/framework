<?php

declare(strict_types=1);

session_start();

require_once dirname(__DIR__) . '/config/const.php';

require_once ROOT . '/vendor/autoload.php';

// Configuration des erreurs
$errorDisplay = DEV ? '1' : '0';

ini_set('display_errors', $errorDisplay);
ini_set('display_startup_errors', $errorDisplay);
error_reporting(DEV ? E_ALL : 0);

try {
    new Core\App();
} catch (Exception $e) {
    (new Core\Controller\ErrorController())->exceptionError($e);
}

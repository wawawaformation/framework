<?php

declare(strict_types=1);

/**
 * Configure les variables d'environnement
 * Instancie l'objet Request et Response (PSR-7)
 * Appelle le routeur
 */

namespace Core;

use Core\Controller\ErrorController;
use Dotenv\Dotenv;

/**
 * Classe principale de l'application.
 *
 * Gère le chargement des variables d'environnement et le routage des requêtes.
 */
class App
{
    /**
     * Variables d'environnement présentes dans le fichier .env.
     * @var object
     */
    public static object $config;

    /**
     * Constructeur de la classe.
     *
     * Charge les variables d'environnement puis appelle le routeur.
     * @throws \Exception Si des clés de configuration obligatoires sont manquantes.
     */
    public function __construct()
    {
        // Enregistrement des gestionnaires globaux d'erreurs
        $this->registerErrorHandlers();


        // Charge les variables d'environnement depuis le fichier .env
        $dotenv = Dotenv::createImmutable(ROOT);
        $dotenv->load();

        // Vérification des clés de configuration obligatoires
        $requiredKeys = ['DB_HOST', 'DB_USER', 'DB_PASS'];
        foreach ($requiredKeys as $key) {
            if (!isset($_ENV[$key])) {
                throw new \Exception("La clé de configuration $key est manquante dans .env");
            }
        }

        // Stocke les variables d'environnement dans une propriété statique
        self::$config = (object) $_ENV;

        // Initialise le routeur
        $this->initializeRouter();
    }




    /**
     * Enregistre les gestionnaires globaux d'erreurs et d'exceptions pour l'application.
     *
     * Ce mécanisme permet de capturer :
     * - Toutes les exceptions non attrapées (Throwable) via set_exception_handler().
     * - Toutes les erreurs PHP (warnings, notices...) en les transformant en ErrorException via set_error_handler().
     * - Toutes les erreurs fatales (E_ERROR, E_PARSE, etc.) via register_shutdown_function().
     *
     * Les erreurs et exceptions sont ensuite déléguées à l'ErrorController pour affichage ou traitement.
     *
     * @return void
     */
    private function registerErrorHandlers(): void
    {
        // Handler pour les exceptions non capturées
        set_exception_handler(function (\Throwable $e) {
            (new ErrorController())->exceptionError($e);
        });

        // Handler pour les erreurs PHP (warnings, notices...)
        set_error_handler(function ($severity, $message, $file, $line) {
            throw new \ErrorException($message, 0, $severity, $file, $line);
        });

        // Handler pour les erreurs fatales (ex: out of memory)
        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
                (new ErrorController())->fatalError($error);
            }
        });
    }

    /**
     * Retourne toutes les variables d'environnement définies dans le fichier .env.
     *
     * @return object Un objet contenant les variables d'environnement.
     */
    public static function getConfig(): object
    {
        return self::$config;
    }

    /**
     * Retourne une variable d'environnement spécifique.
     *
     * @param string $key La clé de la variable d'environnement.
     * @param mixed $default La valeur par défaut à retourner si la variable n'est pas trouvée.
     * @return mixed La valeur de la variable ou la valeur par défaut.
     */
    public static function get(string $key, $default = null)
    {
        if (!isset(self::$config->$key)) {
            error_log("Configuration manquante : $key");
            return $default;
        }

        return self::$config->$key;
    }

    /**
     * Initialise le routeur et gère les requêtes entrantes.
     */
    private function initializeRouter()
    {
        $router = new \Core\Controller\Router(); // Délégation au routeur
        $router->handleRequest();
    }
}

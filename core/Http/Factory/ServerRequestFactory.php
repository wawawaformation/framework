<?php

declare(strict_types=1);

namespace Core\Http\Factory;

use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Fabrique une instance de ServerRequest PSR-7 à partir des superglobales PHP.
 *
 * Cette classe utilise Guzzle PSR-7 pour créer une requête HTTP conforme à l'interface
 * `ServerRequestInterface` à partir de `$_SERVER`, `$_GET`, `$_POST`, `$_COOKIE`, `$_FILES`.
 *
 * Méthodes fournies :
 * - `fromGlobals()` : crée la requête complète avec méthode, URI, en-têtes, corps, etc.
 * - `buildUriFromGlobals()` : reconstitue une URI complète à partir des superglobales.
 * - `getAllHeaders()` : extrait les en-têtes HTTP depuis `$_SERVER`.
 * - `normalizeFiles()` : (à compléter) pour transformer `$_FILES` en objets PSR-7.
 *
 * @package Core\Http\Factory
 */
class ServerRequestFactory
{
    /**
     * Crée une instance ServerRequest à partir des variables globales PHP.
     *
     * Cette méthode récupère les informations de la requête courante, telles que :
     * - la méthode HTTP (GET, POST, etc.)
     * - l’URI reconstituée
     * - les en-têtes HTTP
     * - le corps de la requête (php://input)
     * - les paramètres GET, POST, COOKIE et fichiers
     *
     * @return ServerRequestInterface Une requête PSR-7 complète et prête à être utilisée.
     */
    public static function fromGlobals(): ServerRequestInterface
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = self::buildUriFromGlobals();

        $headers = self::getAllHeaders();
        $body = Utils::streamFor(fopen('php://input', 'r'));

        $request = new ServerRequest($method, $uri, $headers, $body, $_SERVER['SERVER_PROTOCOL'] ?? '1.1', $_SERVER);

        return $request
            ->withCookieParams($_COOKIE)
            ->withQueryParams($_GET)
            ->withParsedBody($_POST)
            ->withUploadedFiles(self::normalizeFiles($_FILES));
    }

    /**
     * Construit une URI complète à partir des variables `$_SERVER`.
     *
     * @return string URI complète sous forme de chaîne (ex: "http://localhost/path")
     */
    private static function buildUriFromGlobals(): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';

        return $scheme . '://' . $host . $requestUri;
    }

    /**
     * Extrait les en-têtes HTTP depuis les variables `$_SERVER`.
     *
     * Utilise la fonction `getallheaders()` si disponible, sinon reconstitue
     * manuellement les en-têtes à partir des clés `HTTP_*`.
     *
     * @return array<string, string> Tableau associatif des en-têtes HTTP.
     */
    private static function getAllHeaders(): array
    {
        if (\function_exists('getallheaders')) {
            return getallheaders();
        }

        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (str_starts_with($name, 'HTTP_')) {
                $header = str_replace('_', '-', strtolower(substr($name, 5)));
                $headers[$header] = $value;
            }
        }
        return $headers;
    }

    /**
     * Normalise les fichiers uploadés depuis `$_FILES` vers des objets PSR-7.
     *
     * ⚠️ Cette méthode est actuellement un stub et retourne un tableau vide.
     *
     * @param array $files Tableau brut issu de $_FILES.
     * @return array Tableau d’objets `UploadedFileInterface` (à implémenter).
     */
    private static function normalizeFiles(array $files): array
    {
        return []; // TODO : Implémenter la normalisation des fichiers
    }
}

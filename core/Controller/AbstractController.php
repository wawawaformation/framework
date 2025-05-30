<?php

declare(strict_types=1);

namespace Core\Controller;


use Core\Controller\Trait\FlashTrait;
use Core\View\ViewTrait;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;


/**
 * Classe de base pour tous les contrôleurs.
 *
 * Fournit des méthodes utilitaires pour le rendu des vues,
 * la gestion des layouts, et des blocs dynamiques (titre, contenu, etc.).
 */
abstract class AbstractController
{

    use ViewTrait;
    use FlashTrait;


    /**
     * Rend une vue HTML encapsulée dans un layout.
     *
     * @param string $view Nom de la vue (ex: "home/index").
     * @param array $data Données passées à la vue.
     * @param string $layout Nom du layout (par défaut: layout-base).
     * @return ResponseInterface
     */
    public function render(string $view, array $data = [], string $layout = 'layout-base'): ResponseInterface
    {


        $viewPath = ROOT . '/views/' . $view . '.php';
        $layoutPath = ROOT . '/views/layouts/' . $layout . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("Vue introuvable : $viewPath");
        }

        if (!file_exists($layoutPath)) {
            throw new \RuntimeException("Layout introuvable : $layoutPath");
        }

        // Injection des variables dans la vue
        extract($data, \EXTR_SKIP);

        ob_start();
        require $viewPath;
        $content = trim(ob_get_clean());

        // Si aucun bloc "main" n’a été défini explicitement, on l’utilise par défaut
        if (!isset($this->blocks['main'])) {
            $this->blocks['main'] = $content;
        }

        ob_start();
        require $layoutPath;
        $html = ob_get_clean();

        return $this->applyDefaultHeaders(
            new Response(
                200,
                [], // headers seront ajoutés dans applyDefaultHeaders()
                $html
            )
        );
    }


    /**
     * Retourne une reponse de type JSON
     * @param array $data les données à renvoyer
     * @param int $status code HTTP (200 par default)
     * @return ResponseInterface
     */
    protected function json(array $data, int $status = 200): ResponseInterface
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $this->applyDefaultHeaders(
            new Response($status, ['Content-Type' => 'application/json'], $json)
        );
    }



    /**
     * Redirection
     * @param string $url cible
     * @param int $code code_hhtp 302 par default
     * @return Response
     */
    public function redirect(string $url, int $code = 302): ResponseInterface
    {
        return new Response($code, ['Location' => $url]);
    }






    /**
     * Ajoute des entetes
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return ResponseInterface
     */
    protected function applyDefaultHeaders(ResponseInterface $response): ResponseInterface
    {
        return $response
            ->withHeader('Content-Type', 'text/html; charset=utf-8')
            ->withHeader('X-Frame-Options', 'DENY')
            ->withHeader('X-Content-Type-Options', 'nosniff')
            ->withHeader('X-XSS-Protection', '1; mode=block')
            ->withHeader('Referrer-Policy', 'no-referrer')
            ->withHeader('Permissions-Policy', 'geolocation=(), microphone=()');
    }
}

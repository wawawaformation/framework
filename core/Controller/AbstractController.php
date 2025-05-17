<?php

declare(strict_types=1);

namespace Core\Controller;


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

        return new Response(
            200,
            ['Content-Type' => 'text/html'],
            $html
        );
    }


    public function redirect(string $url, int $code = 302) : ResponseInterface
    {
        return new Response($code, ['Location'=>$url]);
    }
}

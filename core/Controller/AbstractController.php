<?php

declare(strict_types=1);

/**
 * @package Core\Controller
 */

namespace Core\Controller;

/**
 * Classe abstraite pour les contrôleurs.
 *
 * Fournit les fonctionnalités de base pour les contrôleurs, dont le rendu des vues
 * et la gestion des messages flash.
 */
abstract class AbstractController
{
    protected array $params=[];



    /**
     * Récupère les paramètres de configuration.
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Rendu d'une vue spécifique avec contrôle d'existence.
     *
     * @param string $view Le fichier de la vue à appeler.
     * @param array|null $data Les données à transmettre à la vue.
     * @return void
     *
     * @throws \RuntimeException si le fichier de vue est introuvable.
     */
    protected function render(string $view, array $params = []): void
    {
        $view = ltrim($view, '/'); // enlève un éventuel slash au début
        if (str_ends_with($view, '.php')) {
            $viewPath = ROOT . '/view/' . $view; // vue déjà complète
        } else {
            $viewPath = ROOT . '/view/' . $view . '.php'; // ajoute l'extension .php
        }
    
        if (!file_exists($viewPath)) {
          
            throw new \RuntimeException("Vue introuvable : $viewPath");
        }
    
        extract($params, EXTR_OVERWRITE);
        require_once $viewPath;
    }

    /**
     * Redirection vers une autre page.
     *
     * @param string $url L'URL de redirection.
     * @return void
     */
    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit();
    }

    /**
     * Ajoute un message flash à la session.
     * Permet d'enregistrer plusieurs messages.
     *
     * @param string $type Le type du message (success, error, warning…).
     * @param string $message Le contenu du message.
     * @return void
     */
    public function addFlash(string $type, string $message): void
    {
        $_SESSION['flashes'][] = [
            'type' => $type,
            'content' => $message
        ];
    }
}

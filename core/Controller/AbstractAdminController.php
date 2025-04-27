<?php

declare(strict_types=1);

namespace Core\Controller;

/**
 * Classe abstraite pour les contrôleurs d'administration.
 *
 * Fournit les méthodes de sécurité liées à l'accès à l'espace admin.
 */
abstract class AbstractAdminController extends AbstractController
{
    /**
     * Vérifie que l'utilisateur est connecté en tant qu'admin.
     *
     * Si l'utilisateur n'est pas connecté :
     * - Ajoute un message flash
     * - Redirige vers la page de connexion
     *
     * @return void
     */
    protected function isConnected(): void
    {
        if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
            $this->addFlash('warning', 'Vous devez être connecté !');
            $this->redirect('/admin/login');
        }
    }
}

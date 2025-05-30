<?php

namespace Core\Controller\Trait;

/**
 * FlashTrait
 *
 * Permet de gérer les messages "flash" dans la session.
 * Ces messages sont stockés temporairement (jusqu'à leur affichage),
 * puis supprimés automatiquement.
 */
trait FlashTrait
{
    /**
     * Ajoute un message flash.
     *
     * @param string $type    Type du message (ex: success, error, warning, info).
     * @param string $message Le message à afficher.
     */
    public function addFlash(string $type, string $message): void
    {
        if (!isset($_SESSION['_flash'])) {
            $_SESSION['_flash'] = [];
        }

        $_SESSION['_flash'][$type][] = $message;
    }

    /**
     * Récupère les messages flash d’un type donné, puis les supprime.
     *
     * @param string $type Type du message.
     * @return array Liste des messages pour ce type.
     */
    public function getFlash(string $type): array
    {
        $messages = $_SESSION['_flash'][$type] ?? [];
        unset($_SESSION['_flash'][$type]);
        return $messages;
    }

    /**
     * Récupère tous les messages flash, puis les supprime.
     *
     * @return array<string, array> Messages groupés par type.
     */
    public function getFlashes(): array
    {
        $flashes = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);
        return $flashes;
    }

    /**
     * Vérifie s'il y a des messages flash en session.
     *
     * @return bool
     */
    public function hasFlash(): bool
    {
        return !empty($_SESSION['_flash']);
    }
}

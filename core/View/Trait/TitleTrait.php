<?php

declare(strict_types=1);

namespace Core\View\Trait;

/**
 * Trait TitleTrait
 *
 * Permet de définir et récupérer dynamiquement le titre de la page.
 * À intégrer dans AbstractController ou un gestionnaire de vues.
 */
trait TitleTrait
{
    /**
     * Titre de la page
     *
     * @var string|null
     */
    protected ?string $title = null;

    /**
     * Définit le titre de la page.
     *
     * @param string $title Le titre à afficher dans la balise <title>.
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Retourne le titre défini, ou une valeur par défaut.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title ?? 'Titre par défaut';
    }
}

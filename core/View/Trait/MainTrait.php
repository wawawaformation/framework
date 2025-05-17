<?php

declare(strict_types=1);

namespace Core\View\Trait;

/**
 * Trait MainTrait
 *
 * Fournit une API dédiée à la gestion du bloc `main` dans une vue,
 * en s’appuyant sur BlockTrait (start/end/block).
 */
trait MainTrait
{
    use BlockTrait;

    /**
     * Démarre la capture du bloc `main`.
     *
     * À appeler dans la vue avant le contenu principal.
     *
     * @return void
     */
    public function mainStart(): void
    {
        $this->start('main');
    }

    /**
     * Termine la capture du bloc `main`.
     *
     * À appeler dans la vue après le contenu principal.
     *
     * @return void
     */
    public function mainEnd(): void
    {
        $this->end();
    }

    /**
     * Rendu du contenu du bloc `main`.
     *
     * À appeler depuis le layout pour insérer le contenu principal.
     *
     * @return string
     */
    public function renderMain(): string
    {
        return $this->block('main');
    }
}

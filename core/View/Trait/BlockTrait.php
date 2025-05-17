<?php

declare(strict_types=1);

namespace Core\View\Trait;

/**
 * Gère la capture et l'affichage de blocs de contenu dynamiques dans les vues.
 *
 * À utiliser dans un contrôleur pour encapsuler du contenu que les layouts pourront afficher.
 */
trait BlockTrait
{
    /**
     * Stocke les blocs de contenu capturés dans la vue.
     *
     * @var array<string, string>
     */
    protected array $blocks = [];

    /**
     * Nom du bloc en cours de capture.
     *
     * @var string|null
     */
    protected ?string $currentBlock = null;

    /**
     * Démarre la capture d’un bloc de contenu à utiliser dans le layout.
     *
     * @param string $name Nom du bloc à démarrer.
     * @throws \LogicException Si un autre bloc est déjà en cours de capture.
     */
    public function start(string $name): void
    {
        if ($this->currentBlock !== null) {
            throw new \LogicException("Un bloc est déjà en cours ({$this->currentBlock}). Vous devez appeler end() avant d'en démarrer un autre.");
        }

        $this->currentBlock = $name;
        ob_start();
    }

    /**
     * Termine la capture du bloc en cours et enregistre son contenu.
     *
     * @throws \LogicException Si aucun bloc n’a été démarré.
     */
    public function end(): void
    {
        if ($this->currentBlock === null) {
            throw new \LogicException("Aucun bloc n’a été démarré. Impossible d’appeler end().");
        }

        $this->blocks[$this->currentBlock] = trim(ob_get_clean());
        $this->currentBlock = null;
    }

    /**
     * Récupère le contenu d’un bloc capturé.
     *
     * @param string $name Nom du bloc.
     * @return string Contenu du bloc ou chaîne vide.
     */
    public function block(string $name): string
    {
        return $this->blocks[$name] ?? '';
    }
}

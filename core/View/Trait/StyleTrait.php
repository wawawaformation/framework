<?php

declare(strict_types=1);

namespace Core\View\Trait;

/**
 * StyleTrait
 *
 * Gère les feuilles de style à injecter dans le layout.
 * Gère deux priorités : layout (général) et template (spécifique).
 */
trait StyleTrait
{
    use EscaperTrait;

    /**
     * Feuilles de style ajoutées dans le layout (globales).
     *
     * @var array<string>
     */
    protected array $layoutStyles = [];

    /**
     * Feuilles de style ajoutées dans les vues (spécifiques à une page).
     *
     * @var array<string>
     */
    protected array $templateStyles = [];

    /**
     * Ajoute une feuille de style côté layout (priorité basse).
     *
     * @param string $href
     */
    public function addLayoutStyle(string $href): void
    {
        $this->layoutStyles[] = $href;
    }

    /**
     * Ajoute une feuille de style côté template (priorité haute).
     *
     * @param string $href
     */
    public function addTemplateStyle(string $href): void
    {
        $this->templateStyles[] = $href;
    }

    /**
     * Rendu HTML des balises `<link>` dans l’ordre layout > template.
     *
     * @return string
     */
    public function renderStyles(): string
    {
        $html = '';

        foreach (array_merge($this->layoutStyles, $this->templateStyles) as $href) {
            $html .= '<link rel="stylesheet" href="' . $this->e($href) . '">' . "\n";
        }

        return $html;
    }

    /**
     * Vérifie s’il y a au moins une feuille de style à inclure.
     */
    public function hasStyles(): bool
    {
        return !empty($this->layoutStyles) || !empty($this->templateStyles);
    }
}

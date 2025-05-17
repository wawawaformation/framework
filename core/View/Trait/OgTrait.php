<?php

declare(strict_types=1);

namespace Core\View\Trait;

/**
 * Gère les balises Open Graph (OG) + description commune SEO/OG.
 */
trait OgTrait
{
    use MetaTrait;

    protected array $opengraph = [];

    /**
     * Description courte de la page (meta + og).
     *
     * @var string|null
     */
    protected ?string $description = null;

    /**
     * Définit une description courte (meta classique + og:description).
     *
     * @param string $text
     */
    public function setDescription(string $text): void
    {
        $this->description = $text;
        $this->addMeta('description', $text); // <meta name="description">
        $this->opengraph['og:description'] = $text;
    }

    /**
     * Définit les balises OG principales (hors description).
     */
    public function setOpengraph(string $title, string $url, string $image, string $type = 'website', array $otherProperties = []): void
    {
        $this->opengraph = array_merge([
            'og:title' => $title,
            'og:url' => $url,
            'og:image' => $image,
            'og:type' => $type,
        ], $otherProperties);

        // Ajout automatique si la description est définie
        if ($this->description) {
            $this->opengraph['og:description'] = $this->description;
        }
    }

    public function renderOpengraph(): string
    {
        $html = '';
        foreach ($this->opengraph as $property => $content) {
            $html .= '<meta property="' . $this->e($property) . '" content="' . $this->e($content) . '">' . "\n";
        }
        return $html;
    }
}

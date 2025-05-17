<?php

declare(strict_types=1);

namespace Core\View\Trait;

trait MetaTrait
{
    use EscaperTrait;

    /**
     * Liste des balises meta.
     *
     * @var array<array{name: string, content: string, property: string|null}>
     */
    protected array $metas = [];



    /**
     * Ajoute une meta à la liste
     * @param string $name attribut name
     * @param string $content attribut contenu
     * @param mixed $property attribut property pour les opengraph par exemple
     * @return void
     */
    public function addMeta(string $name, string $content, ?string $property = null): void
    {
        $this->metas[] = [
            'name' => $name,
            'content' => $content,
            'property' => $property
        ];
    }


    /**
     * Contient des meta
     * @return bool
     */
    public function hasMeta(): bool
    {
        return !empty($this->metas);
    }

    /**
     * Ecris la liste des meta
     * @return string
     */
    protected function renderMeta(): string
    {
        $html = '';
        foreach ($this->metas as $meta) {
            if (!empty($meta['property'])) {
                $html .= '<meta property="' . $this->e($meta['property']) . '" content="' . $this->e($meta['content']) . "\">\n";
            } else {
                $html .= '<meta name="' . $this->e($meta['name']) . '" content="' . $this->e($meta['content']) . "\">\n";
            }
        }
        return $html;
    }
}

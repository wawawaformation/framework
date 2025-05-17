<?php

declare(strict_types=1);

namespace Core\View\Trait;

/**
 * Trait ScriptTrait
 *
 * Gère l’injection de scripts JavaScript dans le layout (head ou footer).
 */
trait ScriptTrait
{
    use EscaperTrait;
    /**
     * Liste des scripts à insérer dans le <head>
     *
     * @var array<array{src: string, defer: bool}>
     */
    protected array $headerScripts = [];

    /**
     * Liste des scripts à insérer avant la fermeture du </body>
     *
     * @var string[]
     */
    protected array $footerScripts = [];

    public const HEADER = 'header';
    public const FOOTER = 'footer';

    /**
     * Ajoute un script à placer dans le head du document.
     *
     * @param string $src URL du script.
     * @param bool $defer Si true, ajoute l'attribut defer.
     */
    public function addHeaderScript(string $src, bool $defer = false): void
    {
        $this->headerScripts[] = [
            'src' => $src,
            'defer' => $defer
        ];
    }

    /**
     * Ajoute un script à placer dans le footer du document.
     *
     * @param string $src URL du script.
     */
    public function addFooterScript(string $src): void
    {
        $this->footerScripts[] = $src;
    }

    /**
     * Génère le HTML des balises <script> pour une zone donnée.
     *
     * @param string $where 'header' ou 'footer'.
     * @return string Code HTML des <script>.
     */
    public function renderScripts(string $where): string
    {
        $html = '';

        if ($where === self::HEADER) {
            foreach ($this->headerScripts as $script) {
                $html .= '<script src="' . $this->e($script['src']) . '"'
                       . ($script['defer'] ? ' defer' : '')
                       . '></script>' . \PHP_EOL;
            }
        } elseif ($where === self::FOOTER) {
            foreach ($this->footerScripts as $src) {
                $html .= '<script src="' . $this->e($src) . '"></script>' . \PHP_EOL;
            }
        }

        return $html;
    }


}

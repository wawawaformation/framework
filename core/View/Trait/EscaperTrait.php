<?php

declare(strict_types=1);

namespace Core\View\Trait;

/**
 * EscaperTrait
 *
 * Fournit une méthode d'échappement HTML pour sécuriser l'affichage des données dans les vues.
 *
 * Ce trait est destiné à être utilisé dans les contrôleurs ou gestionnaires de vues.
 * Il permet de prévenir les attaques de type XSS (Cross-Site Scripting) en échappant
 * les caractères spéciaux HTML (comme <, >, ", ', &).
 *
 * Exemple :
 * ```php
 * echo $this->e('<script>alert("XSS")</script>');
 * // Affichera : &lt;script&gt;alert(&quot;XSS&quot;)&lt;/script&gt;
 * ```
 *
 * @package Core\View\Trait
 */
trait EscaperTrait
{
    /**
     * Protège les données affichées contre les attaques XSS en échappant les caractères spéciaux HTML.
     *
     * @param string $string La chaîne à sécuriser.
     * @return string La chaîne échappée, prête à être injectée dans du HTML.
     */
    public function e(string $string): string
    {
        return htmlspecialchars($string, \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8');
    }
}

<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/core')
    ->in(__DIR__.'/public')
    ->in(__DIR__.'/config')
    ->exclude('vendor')
    ->exclude('node_modules');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'strict_param' => true, // Oblige à passer des types stricts aux fonctions natives PHP
        'no_unused_imports' => true, // Supprime les use inutiles
        'ordered_imports' => ['sort_algorithm' => 'alpha'], // Trie les imports par ordre alphabétique
        'declare_strict_types' => true, // Ajoute `declare(strict_types=1);` en haut des fichiers PHP
        'single_quote' => true, // Préfère les quotes simples ' sauf si nécessaire
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true); // Autorise les règles considérées comme "risky" (ex: strict_types)

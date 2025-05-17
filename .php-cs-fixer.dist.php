<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/config')
    ->in(__DIR__ . '/core');

return (new Config())
    ->setRules([
        '@PSR12' => true,
        '@Symfony:risky' => true,
        'strict_param' => true,
        'no_unused_imports' => true,
        'declare_strict_types' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);

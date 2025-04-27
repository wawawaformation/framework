<?php

$extensions = ['php', 'html', 'env', 'css', 'scss', 'js', 'json', 'yml', 'twig']; // Ajoute ce que tu veux
$totalLines = 0;

$excludedPaths = ['/vendor/', '/node_modules/', '/node_module/'];

$directory = new RecursiveDirectoryIterator(dirname(__DIR__), RecursiveDirectoryIterator::SKIP_DOTS);
$iterator = new RecursiveIteratorIterator($directory);

foreach ($iterator as $file) {
    $path = $file->getPathname();

    // On saute les fichiers dans les dossiers à ignorer
    foreach ($excludedPaths as $excluded) {
        if (strpos($path, $excluded) !== false) {
            continue 2; // saute à la prochaine itération du foreach principal
        }
    }

    $ext = pathinfo($file, PATHINFO_EXTENSION);
    if (in_array($ext, $extensions)) {
        $lines = count(file($path));
        $totalLines += $lines;
    }
}

$kilo = round($totalLines / 1000);

echo "Il y a environ $kilo milliers de lignes de code dans ce projet (~$totalLines lignes)\n";

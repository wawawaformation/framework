<?php 

/**
 * @var \Core\Controller\AbstractController $this 
 *  @var \Core\Controller\AbstractController::renderMeta renderMeta 
 * */


$this->addLayoutStyle('https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->e($this->getTitle() ?? 'Mon site') ?></title>
    <?= $this->renderMeta() ?>
    <?= $this->renderStyles() ?>
    <?= $this->renderScripts('header') ?>
</head>
<body>

    <?= $this->renderMain() ?>

    <?= $this->renderScripts('footer') ?>
</body>
</html>

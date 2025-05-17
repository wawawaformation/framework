<?php
/** @var \Core\Controller\AbstractController $this */

$this->setTitle('Bienvenue sur mon site Bootstrapé');
$this->addMeta('description', 'Exemple d’intégration Bootstrap avec notre moteur de vues');

$this->addFooterScript('https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js');

$this->mainStart();
?>

<div class="container mt-5">
    <div class="row">
        <div class="col text-center">
            <h1 class="display-4">Bienvenue 👋</h1>
            <p class="lead">Ceci est une page d’accueil utilisant Bootstrap 5 et ton propre système de vues.</p>
            <a href="#" class="btn btn-primary">En savoir plus</a>
        </div>
    </div>
</div>

<?php
$this->mainEnd();
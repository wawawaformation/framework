<?php

declare(strict_types=1);

namespace App\Controller;

use Core\Controller\AbstractController;

class AccueilController extends AbstractController
{
    public function index()
    {

        $this->render('/front/accueil.php');
    }
}

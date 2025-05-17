<?php

declare(strict_types=1);

namespace App\Controller;

use Core\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface;

class HomeController extends AbstractController
{
    public function index()
    {

        return $this->render('home/index', ['name' => 'David']);
    }

    public function autre(): ResponseInterface
    {
        return $this->redirect('https://www.google.fr/');
    }
}

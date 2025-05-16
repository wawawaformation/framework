<?php

namespace App\Controller;

use GuzzleHttp\Psr7\Response;

class HomeController
{
    public function index()
    {
        dump('controller: HomeController', 'méthode: index');
        return new Response(
            200,
            ['Content-Type' => 'text/html'],
            '<h1>Accueil</h1>'
        );
    }
}
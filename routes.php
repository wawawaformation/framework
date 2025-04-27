<?php

    /**
     * Liste des  routes
     */
 
/*=============================================================
*  FRONT
=============================================================*/

$router->map('GET', '/', function(){
  (new App\Controller\AccueilController())->index();
});





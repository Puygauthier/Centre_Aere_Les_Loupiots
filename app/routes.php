<?php
/** @var \App\Core\Router $router */

// Accueil
$router->get('/', 'HomeController@index');

// Enfants
$router->get('/enfants', 'EnfantController@index');

// Formulaire + traitement d'ajout
$router->get('/enfants/ajouter', 'EnfantController@create');  // GET
$router->post('/enfants/ajouter', 'EnfantController@store');  // POST



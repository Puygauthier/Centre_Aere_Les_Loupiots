<?php
/** @var \App\Core\Router $router */

// Accueil : liste des activités
$router->get('/', 'HomeController@index');

// Formulaire d'inscription + envoi
$router->get('/inscriptions/create', 'InscriptionController@create');
$router->post('/inscriptions', 'InscriptionController@store');

// API certifs requis pour une activité (AJAX)
$router->get('/activites/{id}/certifs', 'ActiviteController@certifs');

// Staff : valider un certificat
$router->post('/certificats/valider', 'CertificatController@valider');

$router->get('/inscriptions', 'InscriptionController@index');

$router->get('/activites/{id}/inscriptions', 'ActiviteController@inscriptions');


$router->get('/login', 'AuthController@form');
$router->post('/login', 'AuthController@login');
$router->post('/logout', 'AuthController@logout');


$router->get('/staff/certificats', 'CertificatController@index');
$router->post('/certificats/valider', 'CertificatController@valider');

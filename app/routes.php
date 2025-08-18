<?php
/** @var \App\Core\Router $router */

// Accueil : liste des activités
$router->get('/', 'HomeController@index');

// Formulaire inscription + POST
$router->get('/inscriptions/create', 'InscriptionController@create');
$router->post('/inscriptions', 'InscriptionController@store');

// API: certificats requis pour une activité (AJAX)
$router->get('/activites/{id}/certifs', 'ActiviteController@certifs');

// Staff: valider un certificat
$router->post('/certificats/valider', 'CertificatController@valider');

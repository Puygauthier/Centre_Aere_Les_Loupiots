<?php
namespace App\Controllers;

use App\Models\Activite;

class HomeController {
  public function index(): void {
    $activites = Activite::all(); // liste pour l'accueil
    include ROOT . '/app/views/inscriptions/home.php';
  }
}

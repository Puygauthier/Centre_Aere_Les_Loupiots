<?php
namespace App\Controllers;

use App\Models\Activite;

class HomeController {
  public function index(): void {
    $activites = Activite::all();
    include ROOT . '/app/views/inscriptions/home.php';
  }
}

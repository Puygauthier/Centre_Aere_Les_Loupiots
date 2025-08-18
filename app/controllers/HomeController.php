<?php
namespace App\Controllers;

class HomeController {
  public function index(): void {
    $title = 'Centre Aéré - Accueil';
    $contentView = ROOT . '/app/views/home/index.php';
    require ROOT . '/app/views/layout.php';
  }
}

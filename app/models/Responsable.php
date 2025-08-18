<?php
namespace App\Models;

use App\Core\Database;

class Responsable {
  public static function all(): array {
    return Database::getPdo()
      ->query("SELECT id, nom, prenom FROM responsables ORDER BY nom, prenom")
      ->fetchAll();
  }
}

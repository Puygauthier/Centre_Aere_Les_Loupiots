<?php
namespace App\Models;

use App\Core\Database;

class Inscription
{
  public static function creer(int $enfantId, int $activiteId, string $statut='en_attente'): int {
    $pdo = Database::getPdo();
    $st = $pdo->prepare("INSERT INTO inscriptions (enfant_id, activite_id, statut) VALUES (?,?,?)");
    $st->execute([$enfantId, $activiteId, $statut]);
    return (int)$pdo->lastInsertId();
  }

  public static function valider(int $id): void {
    $pdo = Database::getPdo();
    $st = $pdo->prepare("UPDATE inscriptions SET statut='valide' WHERE id=?");
    $st->execute([$id]);
  }
}


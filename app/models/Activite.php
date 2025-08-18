<?php
namespace App\Models;

use App\Core\Database;

class Activite {
  public static function all(): array {
    $pdo = Database::getPdo();
    return $pdo->query("SELECT * FROM activites ORDER BY date_debut")->fetchAll();
  }
  public static function find(int $id): ?array {
    $pdo = Database::getPdo();
    $st = $pdo->prepare("SELECT * FROM activites WHERE id=?");
    $st->execute([$id]);
    $r = $st->fetch(); return $r ?: null;
  }
  public static function placesRestantes(int $activiteId): int {
    $pdo = Database::getPdo();
    $sql = "SELECT capacity - (SELECT COUNT(*) FROM inscriptions WHERE activite_id=? AND statut='valide')
            FROM activites WHERE id=?";
    $st = $pdo->prepare($sql);
    $st->execute([$activiteId, $activiteId]);
    return (int)$st->fetchColumn();
  }
  public static function certificatsRequis(int $activiteId): array {
    $pdo = Database::getPdo();
    $sql = "SELECT ct.id, ct.code, ct.libelle
            FROM activite_certificats ac
            JOIN certificat_types ct ON ct.id=ac.certificat_type_id
            WHERE ac.activite_id=?";
    $st = $pdo->prepare($sql);
    $st->execute([$activiteId]);
    return $st->fetchAll();
  }
}

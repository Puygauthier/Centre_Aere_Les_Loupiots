<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Activite
{
  public static function all(): array {
    $pdo = Database::getPdo();
    $sql = "SELECT * FROM activites ORDER BY id DESC";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }

  // Activités à venir avec places restantes > 0
  public static function allOpen(): array {
    $pdo = Database::getPdo();
    $sql = "SELECT a.*,
                   (a.capacity - (
                      SELECT COUNT(*) FROM inscriptions i
                      WHERE i.activite_id = a.id AND i.statut = 'valide'
                   )) AS places_restantes
            FROM activites a
            WHERE (a.date_debut IS NULL OR a.date_debut >= NOW())
              AND (a.capacity - (
                      SELECT COUNT(*) FROM inscriptions i
                      WHERE i.activite_id = a.id AND i.statut = 'valide'
                  )) > 0
            ORDER BY a.date_debut IS NULL, a.date_debut, a.id DESC";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function find(int $id): ?array {
    $pdo = Database::getPdo();
    $st = $pdo->prepare("SELECT * FROM activites WHERE id = ?");
    $st->execute([$id]);
    $row = $st->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
  }

  public static function placesRestantes(int $activiteId): int {
    $pdo = Database::getPdo();
    $sql = "SELECT capacity - (
              SELECT COUNT(*) FROM inscriptions
              WHERE activite_id = ? AND statut = 'valide'
            ) AS restantes
            FROM activites
            WHERE id = ?";
    $st = $pdo->prepare($sql);
    $st->execute([$activiteId, $activiteId]);
    $val = $st->fetchColumn();
    return $val !== false ? (int)$val : 0;
  }

  public static function certificatsRequis(int $activiteId): array {
    $pdo = Database::getPdo();
    try {
      $sql = "SELECT ct.id, ct.code, ct.libelle
              FROM activite_certificats ac
              JOIN certificat_types ct ON ct.id = ac.certificat_type_id
              WHERE ac.activite_id = ?";
      $st = $pdo->prepare($sql);
      $st->execute([$activiteId]);
      return $st->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $e) {
      return [];
    }
  }
}

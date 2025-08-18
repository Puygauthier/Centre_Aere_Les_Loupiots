<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Enfant
{
  /**
   * Liste tous les enfants avec leur responsable principal.
   */
  public static function all(): array {
    $pdo = Database::getPdo();
    $sql = "SELECT e.id, e.nom, e.prenom, e.date_naissance,
                   r.nom AS resp_nom, r.prenom AS resp_prenom
            FROM enfants e
            JOIN responsables r ON r.id = e.responsable_princ
            ORDER BY e.nom, e.prenom";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Crée un enfant et retourne son ID.
   * Attendu: ['nom','prenom','date_naissance(YYYY-MM-DD)','responsable_princ']
   */
  public static function create(array $data): int {
    $pdo = Database::getPdo();
    $stmt = $pdo->prepare(
      "INSERT INTO enfants (nom, prenom, date_naissance, responsable_princ)
       VALUES (:nom, :prenom, :date_naissance, :responsable_princ)"
    );
    $stmt->execute([
      ':nom' => trim((string)($data['nom'] ?? '')),
      ':prenom' => trim((string)($data['prenom'] ?? '')),
      ':date_naissance' => (string)($data['date_naissance'] ?? ''),
      ':responsable_princ' => (int)($data['responsable_princ'] ?? 0),
    ]);
    return (int)$pdo->lastInsertId();
  }

  /**
   * Récupère un enfant par ID (avec responsable).
   */
  public static function find(int $id): ?array {
    $pdo = Database::getPdo();
    $stmt = $pdo->prepare(
      "SELECT e.*, r.nom AS resp_nom, r.prenom AS resp_prenom
       FROM enfants e
       JOIN responsables r ON r.id = e.responsable_princ
       WHERE e.id = :id"
    );
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
  }

  /**
   * Met à jour un enfant (champs autorisés: nom, prenom, date_naissance, responsable_princ).
   */
  public static function update(int $id, array $data): bool {
    $allowed = ['nom','prenom','date_naissance','responsable_princ'];
    $sets = [];
    $params = [':id' => $id];

    foreach ($allowed as $field) {
      if (array_key_exists($field, $data)) {
        $sets[] = "$field = :$field";
        $params[":$field"] = $field === 'responsable_princ'
          ? (int)$data[$field]
          : (is_string($data[$field]) ? trim($data[$field]) : $data[$field]);
      }
    }
    if (!$sets) return false;

    $sql = "UPDATE enfants SET " . implode(', ', $sets) . " WHERE id = :id";
    $pdo = Database::getPdo();
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($params);
  }

  /**
   * Supprime un enfant (les FK liées en cascade s’appliquent).
   */
  public static function delete(int $id): bool {
    $pdo = Database::getPdo();
    $stmt = $pdo->prepare("DELETE FROM enfants WHERE id = :id");
    return $stmt->execute([':id' => $id]);
  }
}

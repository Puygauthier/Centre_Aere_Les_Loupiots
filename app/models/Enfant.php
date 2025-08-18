<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Enfant
{
  public static function all(): array {
    $pdo = Database::getPdo();
    $sql = "SELECT id, nom, prenom, date_naissance
            FROM enfants
            ORDER BY nom, prenom";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function create(array $data): int {
    $pdo = Database::getPdo();
    $stmt = $pdo->prepare(
      "INSERT INTO enfants (nom, prenom, date_naissance)
       VALUES (:nom, :prenom, :date_naissance)"
    );
    $stmt->execute([
      ':nom' => trim((string)($data['nom'] ?? '')),
      ':prenom' => trim((string)($data['prenom'] ?? '')),
      ':date_naissance' => (string)($data['date_naissance'] ?? ''),
    ]);
    return (int)$pdo->lastInsertId();
  }

  public static function find(int $id): ?array {
    $pdo = Database::getPdo();
    $stmt = $pdo->prepare("SELECT * FROM enfants WHERE id=:id");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
  }

  public static function update(int $id, array $data): bool {
    $allowed = ['nom','prenom','date_naissance'];
    $sets = [];
    $params = [':id' => $id];
    foreach ($allowed as $f) {
      if (array_key_exists($f, $data)) {
        $sets[] = "$f=:$f";
        $params[":$f"] = is_string($data[$f]) ? trim($data[$f]) : $data[$f];
      }
    }
    if (!$sets) return false;
    $sql = "UPDATE enfants SET ".implode(', ',$sets)." WHERE id=:id";
    $stmt = Database::getPdo()->prepare($sql);
    return $stmt->execute($params);
  }

  public static function delete(int $id): bool {
    $stmt = Database::getPdo()->prepare("DELETE FROM enfants WHERE id=:id");
    return $stmt->execute([':id' => $id]);
  }
}

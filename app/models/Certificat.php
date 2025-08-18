<?php
namespace App\Models;

use App\Core\Database;

class Certificat {
  public static function enfantPossedeValide(int $enfantId, int $certifTypeId): bool {
    $pdo = Database::getPdo();
    $st = $pdo->prepare("SELECT 1 FROM enfant_certificats WHERE enfant_id=? AND certificat_type_id=? AND statut='valide' LIMIT 1");
    $st->execute([$enfantId, $certifTypeId]);
    return (bool)$st->fetchColumn();
  }

  public static function creerEnAttente(int $enfantId, int $certifTypeId, string $path): void {
    $pdo = Database::getPdo();
    $st = $pdo->prepare("INSERT INTO enfant_certificats(enfant_id, certificat_type_id, fichier_path, statut) VALUES (?,?,?,'en_attente')");
    $st->execute([$enfantId, $certifTypeId, $path]);
  }
}


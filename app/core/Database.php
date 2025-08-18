<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
  private static ?PDO $pdo = null;

  public static function getPdo(): PDO {
    if (self::$pdo === null) {
      $config = require ROOT . '/app/config.php';
      $db = $config['db'];
      $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['name']};charset={$db['charset']}";
      $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
      ];
      try {
        self::$pdo = new PDO($dsn, $db['user'], $db['pass'], $options);
        self::$pdo->exec("SET NAMES {$db['charset']}");
      } catch (PDOException $e) {
        http_response_code(500);
        exit('Erreur connexion DB: ' . $e->getMessage());
      }
    }
    return self::$pdo;
  }
}

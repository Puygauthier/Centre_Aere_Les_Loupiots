<?php
// Afficher toutes les erreurs à l’écran
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

define('ROOT', dirname(__DIR__));

// Autoloader
require ROOT . '/app/core/Autoloader.php';
App\Core\Autoloader::register();

// Config + BASE_PATH
$config = require ROOT . '/app/config.php';
define('BASE_PATH', rtrim($config['base_path'] ?? '', '/'));

// Router
use App\Core\Router;
$router = new Router(BASE_PATH);

// Routes
require ROOT . '/app/routes.php';

// Dispatch avec capture d’erreur pour les afficher
try {
  $router->dispatch();
} catch (Throwable $e) {
  http_response_code(500);
  echo '<pre style="white-space:pre-wrap;font:14px/1.4 monospace">';
  echo "FATAL ERROR: " . htmlspecialchars($e->getMessage()) . "\n\n";
  echo htmlspecialchars($e->getFile()) . ':' . $e->getLine() . "\n\n";
  echo htmlspecialchars($e->getTraceAsString());
  echo '</pre>';
}

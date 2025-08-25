<?php
// Afficher toutes les erreurs à l’écran (dev)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

define('ROOT', dirname(__DIR__));

// Autoloader
require ROOT . '/app/core/Autoloader.php';
App\Core\Autoloader::register();

// Config
$config = require ROOT . '/app/config.php';

// --- Normalisation BASE_PATH ---
// On accepte dans app/config.php :
//   'base_path' => ''            (racine, recommandé avec: php -S ... -t public)
//   ou 'base_path' => 'monprojet/public' (sans slash au début)
//   ou 'base_path' => '/monprojet/public' (avec slash)
// On normalise pour obtenir soit '' soit '/monprojet/public'
$rawBase = isset($config['base_path']) ? (string)$config['base_path'] : '';
$rawBase = trim($rawBase);                  // espaces
$rawBase = trim($rawBase, "/ \t\n\r\0\x0B"); // enlève les / en bord
if ($rawBase === '' || $rawBase === '.') {
    $basePath = '';                         // racine web
} else {
    $basePath = '/' . $rawBase;             // préfixe absolu
}
define('BASE_PATH', $basePath);

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

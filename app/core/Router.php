<?php
namespace App\Core;

class Router {
  private string $basePath;
  private array $routes = ['GET' => [], 'POST' => []];

  public function __construct(string $basePath = '') {
    $basePath = trim($basePath);
    if ($basePath !== '' && $basePath[0] !== '/') $basePath = '/'.$basePath;
    $this->basePath = rtrim($basePath, '/');
  }

  public function get(string $path, string $action): void {
    $this->routes['GET'][$this->normalize($path)] = $action;
  }
  public function post(string $path, string $action): void {
    $this->routes['POST'][$this->normalize($path)] = $action;
  }

  private function normalize(string $path): string {
    $path = '/' . ltrim($path, '/');
    return rtrim($path, '/') ?: '/';
  }

  public function dispatch(): void {
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    if ($method === 'HEAD') $method = 'GET';

    // 1) Priorité au paramètre ?r= (posé par .htaccess)
    if (!empty($_GET['r']) && is_string($_GET['r'])) {
      $uri = $this->normalize($_GET['r']);
    } else {
      // 2) Sinon, fallback REQUEST_URI (au cas où)
      $raw = $_SERVER['REQUEST_URI'] ?? '/';
      $uri = parse_url($raw, PHP_URL_PATH) ?? '/';
      if ($this->basePath !== '' && str_starts_with($uri, $this->basePath)) {
        $uri = substr($uri, strlen($this->basePath)) ?: '/';
      }
      if ($uri === '/index.php') $uri = '/';
      $uri = $this->normalize($uri);
    }

    // Correspondance exacte
    if (isset($this->routes[$method][$uri])) {
      $this->invoke($this->routes[$method][$uri]);
      return;
    }

    // Correspondance avec paramètres {id}
    foreach ($this->routes[$method] as $route => $action) {
      $pattern = preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $route);
      $pattern = '#^' . rtrim($pattern, '/') . '/?$#';
      if (preg_match($pattern, $uri, $m)) {
        $params = array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY);
        $this->invoke($action, $params);
        return;
      }
    }

    http_response_code(404);
    echo '404 - Page non trouvée';
  }

  private function invoke(string $action, array $params = []): void {
    if (strpos($action, '@') === false) {
      http_response_code(500);
      exit('Action invalide : ' . htmlspecialchars($action));
    }
    [$controller, $method] = explode('@', $action, 2);
    $class = "App\\Controllers\\$controller";

    if (!class_exists($class)) { http_response_code(500); exit("Contrôleur $class introuvable"); }
    $obj = new $class();
    if (!method_exists($obj, $method)) { http_response_code(500); exit("Méthode $method introuvable"); }

    call_user_func_array([$obj, $method], $params);
  }
}

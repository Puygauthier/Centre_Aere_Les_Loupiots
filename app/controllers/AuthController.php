<?php
namespace App\Controllers;

class AuthController
{
  public function form(): void {
    include ROOT . '/app/views/auth/login.php';
  }

  public function login(): void {
    $pwd = (string)($_POST['password'] ?? '');
    $config = require ROOT . '/app/config.php';
    $ok = isset($config['staff']['password']) && hash_equals($config['staff']['password'], $pwd);

    if ($ok) {
      $_SESSION['is_staff'] = true;
      $_SESSION['flash'] = ['type'=>'success','text'=>"Connecté (staff)."];
      header('Location: /'); return;
    }
    $_SESSION['flash'] = ['type'=>'error','text'=>"Mot de passe incorrect."];
    header('Location: /login');
  }

  public function logout(): void {
    unset($_SESSION['is_staff']);
    $_SESSION['flash'] = ['type'=>'info','text'=>"Déconnecté."];
    header('Location: /');
  }
}

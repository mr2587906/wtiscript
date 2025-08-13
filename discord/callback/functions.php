<?php
$config = require __DIR__ . '/config.php';

function db() {
  static $pdo = null;
  if ($pdo) return $pdo;
  $c = $GLOBALS['config']['db'];
  $dsn = "mysql:host={$c['host']};dbname={$c['name']};charset={$c['charset']}";
  $pdo = new PDO($dsn, $c['user'], $c['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
  return $pdo;
}

function start_session_safe() {
  if (session_status() === PHP_SESSION_NONE) session_start();
}

function current_user() {
  start_session_safe();
  if (!empty($_SESSION['MM_Username'])) {
    $stmt = db()->prepare('SELECT * FROM memberdata WHERE m_username = ?');
    $stmt->execute([$_SESSION['MM_Username']]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
  return null;
}

function is_vip($user) {
  if (!$user) return false;
  if ($user['is_vip']) {
    if ($user['vip_expire'] === null) return true;
    return (new DateTime($user['vip_expire'])) > new DateTime();
  }
  return false;
}

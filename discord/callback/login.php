<?php
require 'functions.php';
start_session_safe();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location:../callback/'); exit; }
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = db()->prepare('SELECT * FROM memberdata WHERE m_username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user && password_verify($password, $user['password'])) {
  $_SESSION['MM_Username'] = $user['username'];
  header('Location: dashboard.php');
  exit;
}

// 失敗
header('Location:../callback/?err=1');
exit;

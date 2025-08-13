<?php
require 'functions.php';
$config = require 'config.php';
start_session_safe();
$user = current_user();
if (!$user) { header('Location:../callback/'); exit; }

if (empty($_GET['code'])) { echo 'no code'; exit; }
$code = $_GET['code'];

// 1) 交換 token
$data = [
  'client_id' => $config['discord']['client_id'],
  'client_secret' => $config['discord']['client_secret'],
  'grant_type' => 'authorization_code',
  'code' => $code,
  'redirect_uri' => $config['discord']['redirect_uri']
];
$ch = curl_init('https://discord.com/api/oauth2/token');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = json_decode(curl_exec($ch), true);
curl_close($ch);
if (empty($res['access_token'])) { die('token error'); }
$access_token = $res['access_token'];

// 2) 取得使用者資料
$ch = curl_init('https://discord.com/api/users/@me');
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer {$access_token}"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$me = json_decode(curl_exec($ch), true);
curl_close($ch);
if (empty($me['id'])) { die('user fetch failed'); }
$discord_id = $me['id'];

// 3) 用 Bot Token 把使用者加進 Guild（或更新資訊），並給予 role
$guild = $config['discord']['guild_id'];
bot_token = $config['discord']['bot_token'];
$role_id = $config['discord']['role_id'];

$payload = [
  'access_token' => $access_token
];
$ch = curl_init("https://discord.com/api/guilds/{$guild}/members/{$discord_id}");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Authorization: Bot {$bot_token}",
  "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$add_res = json_decode(curl_exec($ch), true);
curl_close($ch);

// 4) 給角色 (PATCH roles)
$ch = curl_init("https://discord.com/api/guilds/{$guild}/members/{$discord_id}");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Authorization: Bot {$bot_token}",
  "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['roles' => [$role_id]]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$role_res = json_decode(curl_exec($ch), true);
curl_close($ch);

// 存回資料庫
$stmt = db()->prepare('UPDATE users SET discord_id = ?, discord_linked_at = NOW() WHERE id = ?');
$stmt->execute([$discord_id, $user['id']]);

header('Location: dashboard.php?linked=1');
exit;

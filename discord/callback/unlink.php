<?php
require 'functions.php';
$config = require 'config.php';
start_session_safe();
$user = current_user();
if (!$user) { header('Location: index.php'); exit; }
if (empty($user['discord_id'])) { header('Location: dashboard.php'); exit; }

$guild = $config['discord']['guild_id'];
$bot_token = $config['discord']['bot_token'];
$discord_id = $user['discord_id'];

// 移除該成員或移除身分組
$ch = curl_init("https://discord.com/api/guilds/{$guild}/members/{$discord_id}");
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bot {$bot_token}"]);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_exec($ch);
curl_close($ch);

// 清除資料庫
$stmt = db()->prepare('UPDATE memberdata SET discord_id = NULL, discord_linked_at = NULL WHERE id = ?');
$stmt->execute([$user['id']]);

header('Location: dashboard.php?unlinked=1');
exit;

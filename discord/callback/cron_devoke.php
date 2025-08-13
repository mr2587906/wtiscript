<?php
// 每日執行：檢查過期 VIP 並移除 Discord 身分組
require 'functions.php';
$config = require 'config.php';
$guild = $config['discord']['guild_id'];
bot_token = $config['discord']['bot_token'];
$role_id = $config['discord']['role_id'];

// 找出過期但仍綁定 discord 的使用者
$stmt = db()->query("SELECT m_id, discord_id FROM memberdata WHERE is_vip = 1 AND vip_expire IS NOT NULL AND vip_expire < NOW() AND discord_id IS NOT NULL");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
  $discord_id = $r['discord_id'];
  // 嘗試移除角色（PATCH空陣列）或直接kick
  $ch = curl_init("https://discord.com/api/guilds/{$guild}/members/{$discord_id}");
  curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bot {$bot_token}"]);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_exec($ch);
  curl_close($ch);

  // 清除 DB
  db()->prepare('UPDATE memberdata SET discord_id = NULL, discord_linked_at = NULL WHERE id = ?')->execute([$r['id']]);
}

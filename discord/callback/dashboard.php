<?php
require 'functions.php';
$config = require 'config.php';
start_session_safe();
$user = current_user();
if (!$user) { header('Location:../callback/'); exit; }
$eligible = is_vip($user);
$client_id = $config['discord']['client_id'];
$redirect = urlencode($config['discord']['redirect_uri']);
$scope = urlencode('identify guilds.join');
$oauth_url = "https://discord.com/api/oauth2/authorize?client_id={$client_id}&redirect_uri={$redirect}&response_type=code&scope={$scope}";
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>會員中心</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <main class="card">
    <h1>歡迎, <?=htmlspecialchars($user['username'])?></h1>
    <p>VIP 狀態: <?= $eligible? '是':'否' ?></p>
    <?php if ($eligible): ?>
      <p><a class="btn" href="<?= $oauth_url ?>">授權 Discord 並加入伺服器</a></p>
    <?php else: ?>
      <p>你目前沒有資格加入專屬 Discord 頻道。</p>
    <?php endif; ?>

    <?php if ($user['discord_id']): ?>
      <p>已綁定 Discord ID: <?=htmlspecialchars($user['discord_id'])?> <a href="unlink.php">解除綁定</a></p>
    <?php endif; ?>

    <p><a href="logout.php">登出</a></p>
  </main>
</body>
</html>

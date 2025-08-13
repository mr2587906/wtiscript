<?php
require 'functions.php';
$config = require 'config.php';
start_session_safe();
if (!empty($_SESSION['user_id'])) {
  header('Location: dashboard.php');
  exit;
}
?>
<!doctype html>
<html>
<style type="text/css">
<!--
input[name="username"], input[name="password"] {
    width: 80%;
    height: 35px;
    border-radius: 10px;
    padding-left: 5px;
    margin-top: 10px;
    margin-bottom: 10px;
}
button[type="submit"] {
    width: 95%;
    height: 45px;
    background: blue;
    color: #fff;
    font-size: 20px;
    font-weight: bold;
    border: none;
    border-radius: 10px;
    margin-left: 5px;
}
h1 {
    width: 100%;
    height: 60px;
    text-align: center;
    background: green;
    line-height: 55px;
    color: #fff;
}
-->
</style>
<head>
  <meta charset="utf-8">
  <title>欣意會員登入</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <main class="card">
    <h1>欣意會員登入</h1>
    <form action="login.php" method="post">
      <label>帳號 <input name="username" required></label>
      <label>密碼 <input type="password" name="password" required></label>
      <button type="submit">登入</button>
    </form>
  </main>
</body>
</html>

大家好我是張添貴，這裡是github，您所在gitfile為：wtiscript

目錄檔案分類

|-wtiscipt.git

|-README.MD

|-discord/callback（Discord伺服器自製第三方個人帳號連接網頁）

目錄1（discord/callback）：

index.php(登入頁)

config.php(資料庫連線含OAuth2 + bot + Discord Server Channel guild ID and User role ID)

login.php(處理登入)

functions.php(共用函式)

dashboard.php(會員檢視與Discord授權按鈕)

discord_callback.php(處理Discord OAuth2回傳並賦予角色)

unlink.php(解除Discord綁定/移除角色)

cron_revoke.php(Discord綁定）

password_hash_add.php（密碼轉hash表單輸入頁）

password_hash.php（生成密碼hash碼結果頁）

assets/（css及js檔目錄）

style.css（網頁特效檔）

app.js（Discord api執行程序檔）

目錄1說明：

1.請到Discord開發平台網站登入您剛註冊的會員帳號並取得您剛新增的伺服器。

2.新增一個客戶端。

3.在客戶端加入大頭照。

4.取得剛新增的客戶端ClientID及Secret複製到config.php貼到Client_id及Select。

5.新增OAuth2。

6.新增bot及redirect_uri。

7.取得剛新增的bot裡的token及redirect_uri並貼到config.php裡的redirect_uri跟bot_token。

8.開啟您的Discord App（電腦版或手機版）。

9.點選您的Discord伺服器。

10.在您的Discord伺服器啟用開發者模式。

11.取得您的伺服器任何一個頻道guild_id及使用者的role_id並貼到config.php裡的guild_id跟role_id。

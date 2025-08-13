// assets/app.js
document.addEventListener('DOMContentLoaded', () => {
  console.log("[App] Loaded");

  // === 1. 登入表單驗證 ===
  const loginForm = document.querySelector('form[action="login.php"]');
  if (loginForm) {
    loginForm.addEventListener('submit', (e) => {
      const username = loginForm.querySelector('input[name="username"]');
      const password = loginForm.querySelector('input[name="password"]');
      if (!username.value.trim() || !password.value.trim()) {
        e.preventDefault();
        showToast("請輸入帳號與密碼", "error");
      }
    });
  }

  // === 2. Discord 授權按鈕狀態 ===
  const discordBtn = document.querySelector('a.btn[href*="discord.com/api/oauth2/authorize"]');
  if (discordBtn) {
    discordBtn.addEventListener('click', (e) => {
      discordBtn.textContent = "正在前往 Discord 授權...";
      discordBtn.classList.add("loading");
    });
  }

  // === 3. 處理 URL 提示訊息 ===
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has("linked")) {
    showToast("✅ Discord 授權成功！", "success");
    clearQueryParams();
  }
  if (urlParams.has("unlinked")) {
    showToast("🔄 Discord 已解除綁定", "info");
    clearQueryParams();
  }
  if (urlParams.has("err")) {
    showToast("❌ 帳號或密碼錯誤", "error");
    clearQueryParams();
  }

  // === Toast 功能 ===
  function showToast(message, type = "info", duration = 3000) {
    const toast = document.createElement("div");
    toast.className = `toast ${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => {
      toast.classList.add("show");
    }, 10);
    setTimeout(() => {
      toast.classList.remove("show");
      setTimeout(() => toast.remove(), 300);
    }, duration);
  }

  // 清除 URL 參數
  function clearQueryParams() {
    window.history.replaceState({}, document.title, window.location.pathname);
  }
});

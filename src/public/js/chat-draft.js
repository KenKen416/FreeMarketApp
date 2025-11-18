// LocalStorage を使ったチャット下書き保持スクリプト
// 読み込み時にページの URL 末尾（purchase id）を取得してキーを作る想定
(function() {
  document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('message_body');
    if (!textarea) return;

    // key に purchase id を含めると複数チャットで分離できる
    const pathParts = window.location.pathname.split('/');
    const purchaseId = pathParts[pathParts.length - 1] || 'global';
    const storageKey = 'chat_draft_' + purchaseId;

    // 復元
    const saved = localStorage.getItem(storageKey);
    if (saved) {
      textarea.value = saved;
    }

    // 入力時に保存（debounce を簡易実装）
    let timeout = null;
    textarea.addEventListener('input', function() {
      if (timeout) clearTimeout(timeout);
      timeout = setTimeout(function() {
        localStorage.setItem(storageKey, textarea.value);
      }, 300);
    });

    // フォーム送信時に下書きを削除
    const form = textarea.closest('form');
    if (form) {
      form.addEventListener('submit', function() {
        localStorage.removeItem(storageKey);
      });
    }
  });
})();
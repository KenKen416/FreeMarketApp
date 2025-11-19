document.addEventListener('DOMContentLoaded', function () {
  const overlay = document.getElementById('editMessageOverlay');
  const modal = overlay?.querySelector('.edit-modal');
  const editForm = document.getElementById('editMessageForm');
  const editBody = document.getElementById('edit_message_body');
  const editPreview = document.getElementById('edit_image_preview');
  const editInputFile = document.getElementById('edit_image');
  const closeBtn = document.getElementById('editModalClose');
  const cancelBtn = document.getElementById('editCancel');

  // open modal and populate form
  function openEditModal({ messageId, purchaseId, messageBody, actionUrl }) {
    if (!overlay || !editForm) return;
    // set form action
    editForm.action = actionUrl || `/chats/${purchaseId}/messages/${messageId}`;
    // set body content
    if (editBody) editBody.value = messageBody || '';
    // clear image preview
    if (editPreview) editPreview.innerHTML = '';
    // show overlay
    overlay.style.display = 'flex';
    overlay.setAttribute('aria-hidden', 'false');
    // focus textarea
    setTimeout(() => editBody && editBody.focus(), 60);
  }

  // close modal
  function closeEditModal() {
    if (!overlay) return;
    overlay.style.display = 'none';
    overlay.setAttribute('aria-hidden', 'true');
  }

  // delegate click on edit links
  document.body.addEventListener('click', function (e) {
    const target = e.target.closest && e.target.closest('.link-edit');
    if (!target) return;
    e.preventDefault();

    const messageId = target.dataset.messageId || target.getAttribute('data-message-id');
    const purchaseId = target.dataset.purchaseId || target.getAttribute('data-purchase-id');
    const messageBody = target.dataset.messageBody || target.getAttribute('data-message-body') || '';
    // optional: data-action-url can override default action
    const actionUrl = target.dataset.actionUrl || target.getAttribute('data-action-url') || null;

    if (!messageId || !purchaseId) return console.warn('missing messageId/purchaseId for edit');
    openEditModal({ messageId, purchaseId, messageBody, actionUrl });
  });

  // close handlers
  closeBtn?.addEventListener('click', closeEditModal);
  cancelBtn?.addEventListener('click', closeEditModal);
  overlay?.addEventListener('click', function (e) {
    if (e.target === overlay) closeEditModal();
  });

  // image preview inside modal
  editInputFile?.addEventListener('change', function () {
    if (!editPreview) return;
    editPreview.innerHTML = '';
    const file = this.files && this.files[0];
    if (!file) return;
    if (!file.type.startsWith('image/')) return;
    const reader = new FileReader();
    reader.onload = function (ev) {
      const img = document.createElement('img');
      img.src = ev.target.result;
      img.style.maxWidth = '120px';
      img.style.maxHeight = '120px';
      editPreview.appendChild(img);
    };
    reader.readAsDataURL(file);
  });
});
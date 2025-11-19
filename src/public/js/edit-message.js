document.addEventListener('DOMContentLoaded', function () {
  const editModal = document.getElementById('editMessageModal');
  const editForm = document.getElementById('editMessageForm');
  const editBody = document.getElementById('edit_message_body');
  const editPreview = document.getElementById('edit_image_preview');
  const editInputFile = document.getElementById('edit_image');
  let currentMessageId = null;

  // delegate clicks for edit links
  document.body.addEventListener('click', function (e) {
    if (e.target.matches('.link-edit')) {
      e.preventDefault();
      // message id should be stored in data-message-id attribute
      const id = e.target.dataset.messageId;
      const body = e.target.dataset.messageBody || '';
      currentMessageId = id;
      editBody.value = body;
      editForm.action = `/chats/${e.target.dataset.purchaseId}/messages/${id}`;
      editModal.style.display = 'flex';
    }
  });

  document.getElementById('editModalClose')?.addEventListener('click', () => editModal.style.display = 'none');

  editInputFile?.addEventListener('change', function () {
    editPreview.innerHTML = '';
    const file = this.files && this.files[0];
    if (!file) return;
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
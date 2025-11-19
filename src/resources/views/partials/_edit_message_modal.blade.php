<div id="editMessageOverlay" class="edit-modal-overlay" aria-hidden="true" style="display:none;">
  <div class="edit-modal" role="dialog" aria-modal="true" aria-labelledby="editModalTitle">
    <button class="edit-modal__close" id="editModalClose" aria-label="閉じる">×</button>

    <h3 id="editModalTitle" class="edit-modal__title">メッセージを編集</h3>

    <form id="editMessageForm" method="POST" enctype="multipart/form-data" class="edit-form">
      @csrf
      @method('PATCH')

      <label for="edit_message_body" class="visually-hidden">本文</label>
      <div class="edit-form__row">
        <textarea name="body" id="edit_message_body" rows="4" class="edit-form__textarea" required></textarea>
      </div>

      <div class="edit-form__row edit-form__file-row">
        <label class="btn-image" for="edit_image">画像を追加</label>
        <input type="file" name="image" id="edit_image" accept=".png,.jpeg,.jpg" class="input-file">
        <div id="edit_image_preview" class="image-preview"></div>
      </div>

      <div class="edit-form__actions">
        <button type="button" class="btn btn-secondary" id="editCancel">キャンセル</button>
        <button type="submit" class="btn btn-primary">更新する</button>
      </div>
    </form>
  </div>
</div>
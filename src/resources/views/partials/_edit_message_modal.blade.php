<!-- メッセージ編集モーダル -->
<div id="editMessageModal" class="modal" style="display:none;">
  <div class="modal-inner">
    <button class="modal-close" id="editModalClose">×</button>
    <h3>メッセージを編集</h3>
    <form id="editMessageForm" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PATCH')
      <div>
        <textarea name="body" id="edit_message_body" rows="4" style="width:100%"></textarea>
      </div>
      <div style="margin-top:8px;display:flex;gap:8px;align-items:center;">
        <label class="btn-image" for="edit_image">画像を追加</label>
        <input type="file" name="image" id="edit_image" accept=".png,.jpeg,.jpg" class="input-file">
        <div id="edit_image_preview"></div>
      </div>
      <div style="margin-top:12px;text-align:right;">
        <button type="submit" class="btn btn-primary">更新する</button>
      </div>
    </form>
  </div>
</div>
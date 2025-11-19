<!-- 評価モーダル（星のみ、コメント不要） -->
<div id="ratingModalOverlay" class="rating-modal-overlay" role="dialog" aria-modal="true" aria-hidden="true" style="display:none;">
  <div class="rating-modal" role="document" aria-labelledby="ratingModalTitle">
    <button class="rating-modal__close" id="ratingModalClose" aria-label="閉じる">×</button>

    <header class="rating-modal__header">
      <h2 id="ratingModalTitle" class="rating-modal__title">取引が完了しました。</h2>
    </header>

    <div class="rating-modal__body">
      <p class="rating-modal__lead">今回の取引相手はどうでしたか？</p>

      <form id="ratingForm" action="{{ route('ratings.store') }}" method="POST" class="rating-form">
        @csrf
        <input type="hidden" name="purchase_id" id="rating_purchase_id" value="{{ $purchase->id ?? '' }}">
        <input type="hidden" name="score" id="rating_score" value="">

        <div class="rating-modal__stars" role="radiogroup" aria-label="評価">
          @for ($i = 1; $i <= 5; $i++)
            <button type="button" class="star" data-value="{{ $i }}" aria-pressed="false" aria-label="{{ $i }}つ星">
            <span class="star-icon">★</span>
            </button>
            @endfor
        </div>

        <div class="rating-modal__footer">
          <button type="submit" class="rating-modal__submit">送信する</button>
        </div>
      </form>
    </div>
  </div>
</div>
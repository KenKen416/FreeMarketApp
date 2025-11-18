<div class="items-list">

  @if($tradePurchases && $tradePurchases->count())
  @foreach($tradePurchases as $purchase)
  <div class="item-card">

    @if($purchase->unread_count > 0)
    <span class="trade-badge">
      {{ $purchase->unread_count > 99 ? '99+' : $purchase->unread_count }}
    </span>
    @endif
    <a href="{{ route('chats.show', $purchase->id) }}" class="item-card__link" aria-label="取引: {{ optional($purchase->item)->name }}">
      <img src="{{ asset('storage/' . $purchase->item->image) }}" alt="{{ optional($purchase->item)->name }}">
    </a>
  </div>
  @endforeach

  @else
  <p class="muted">取引中の商品はありません。</p>
  @endif
  </main>
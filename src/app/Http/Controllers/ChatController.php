<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Purchase;
use App\Models\Message;
use App\Models\Rating; // 追加

class ChatController extends Controller
{


    /**
     * 特定取引のチャット画面
     */
    public function show(Request $request, Purchase $purchase)
    {
        $user = Auth::user();

        // 必要なリレーションをロード
        $purchase->load([
            'item.user.profile',  // 商品の出品者とそのプロフィール
            'user.profile'        // 購入者とそのプロフィール
        ]);

        // 権限チェック：購入者または出品者のみ閲覧可能
        $isBuyer = $purchase->user_id === $user->id;
        $isSeller = $purchase->item && $purchase->item->user_id === $user->id;
        if (!($isBuyer || $isSeller)) {
            abort(403);
        }

        // Sidebar 用の取引一覧を取得（簡易）
        $purchases = Purchase::with('item')
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhereHas('item', function ($q2) use ($user) {
                        $q2->where('user_id', $user->id);
                    });
            })
            ->get()
            ->map(function ($p) use ($user) {
                $p->unread_count = Message::where('purchase_id', $p->id)
                    ->whereNull('read_at')
                    ->where('sender_id', '!=', $user->id)
                    ->count();
                $p->last_message_at = Message::where('purchase_id', $p->id)
                    ->latest('created_at')
                    ->value('created_at') ?? $p->updated_at;
                return $p;
            })
            ->sortByDesc('last_message_at')
            ->values();

        // メッセージを取得（古い順）
        $messages = Message::with('sender')
            ->where('purchase_id', $purchase->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // このユーザーが受け取った未読メッセージを既読にする
        Message::where('purchase_id', $purchase->id)
            ->whereNull('read_at')
            ->where('sender_id', '!=', $user->id)
            ->update(['read_at' => now()]);

        // otherUser: 自分ではない相手ユーザー（Blade のロジックをここで扱う）
        if ($purchase->user_id === $user->id) {
            // 自分が購入者の場合、相手は出品者
            $otherUser = optional($purchase->item)->user;
        } else {
            // 自分が出品者の場合、相手は購入者
            $otherUser = $purchase->user;
        }

        // FN013: 出品者による取引後評価モーダル表示判定
        // showRating = true にする条件：
        //  - 取引が完了している (completed_at がセットされている)
        //  - 現在のユーザーが出品者であること
        //  - 現在のユーザー（出品者）が当該取引でまだ評価（rater）していないこと
        $showRating = false;
        if ($purchase->completed_at && $isSeller) {
            $existing = Rating::where('rater_id', $user->id)
                ->where('purchase_id', $purchase->id)
                ->first();
            if (!$existing) {
                $showRating = true;
            }
        }

        return view('chat.show', compact('purchase', 'messages', 'purchases', 'otherUser', 'showRating'));
    }
}

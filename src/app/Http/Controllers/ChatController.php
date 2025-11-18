<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Purchase;
use App\Models\Message;

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

        return view('chat.show', compact('purchase', 'messages', 'purchases'));
    }
}

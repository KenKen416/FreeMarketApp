<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMessageRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Purchase;
use App\Models\Message;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    /**
     * メッセージ投稿
     */
    public function store(StoreMessageRequest $request, Purchase $purchase)
    {
        $user = Auth::user();

        // 権限チェック（購入者 or 出品者）
        $isBuyer = $purchase->user_id === $user->id;
        $isSeller = $purchase->item && $purchase->item->user_id === $user->id;
        if (!($isBuyer || $isSeller)) {
            abort(403);
        }

        $data = $request->validated();

        // 画像保存（任意）
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('messages', 'public');
            $data['image'] = $path;
        }

        $message = Message::create([
            'purchase_id' => $purchase->id,
            'sender_id' => $user->id,
            'body' => $data['body'],
            'image' => $data['image'] ?? null,
        ]);

        // リダイレクト（チャット画面に戻る）
        return redirect()->route('chats.show', $purchase->id)->with('success', 'メッセージを送信しました');
    }

    // 編集・削除は次フェーズで追加します
}

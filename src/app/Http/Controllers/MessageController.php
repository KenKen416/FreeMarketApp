<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
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

        return redirect()->route('chats.show', $purchase->id)->with('success', 'メッセージを送信しました');
    }

    /**
     * メッセージ編集
     */
    public function update(UpdateMessageRequest $request, Purchase $purchase, Message $message)
    {
        $user = Auth::user();

        // 権限チェック：送信者のみ
        if ($message->sender_id !== $user->id) {
            abort(403);
        }

        $data = $request->validated();

        // 画像保存（任意、上書き）
        if ($request->hasFile('image')) {
            // 既存画像を削除（あれば）
            if ($message->image) {
                Storage::disk('public')->delete($message->image);
            }
            $path = $request->file('image')->store('messages', 'public');
            $message->image = $path;
        }

        $message->body = $data['body'];
        $message->save();

        return redirect()->route('chats.show', $purchase->id)->with('success', 'メッセージを更新しました');
    }

    /**
     * メッセージ削除
     */
    public function destroy(Request $request, Purchase $purchase, Message $message)
    {
        $user = Auth::user();

        if ($message->sender_id !== $user->id) {
            abort(403);
        }

        // 画像削除
        if ($message->image) {
            Storage::disk('public')->delete($message->image);
        }

        $message->delete();

        return redirect()->route('chats.show', $purchase->id)->with('success', 'メッセージを削除しました');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Purchase;
use App\Mail\TransactionCompletedMail;
use Illuminate\Support\Facades\Mail;

class PurchaseCompleteController extends Controller
{
    /**
     * 購入者が取引を完了するアクション
     */
    public function complete(Request $request, Purchase $purchase)
    {
        $user = Auth::user();

        // 完了できるのは購入者のみ（要件通り購入者が完了する想定）
        if ($purchase->user_id !== $user->id) {
            abort(403);
        }

        // 既に完了済みなら何もしない
        if ($purchase->completed_at) {
            return redirect()->route('chats.show', $purchase->id)->with('failed', 'この取引は既に完了済みです');
        }

        $purchase->completed_at = now();
        $purchase->save();

        // 出品者にメール送信
        $seller = $purchase->item->user;
        if ($seller && $seller->email) {
            Mail::to($seller)->send(new TransactionCompletedMail($purchase));
        }

        // 購入者に評価モーダルを表示させるため、チャットに戻る際にフラグを渡す
        return redirect()->route('chats.show', $purchase->id)->with('show_rating', true);
    }
}

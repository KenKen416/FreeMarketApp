<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRatingRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Rating;
use App\Models\Purchase;

class RatingController extends Controller
{
    public function store(StoreRatingRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        $purchase = Purchase::findOrFail($data['purchase_id']);

        // 評価できるのは取引関係者（購入者または出品者）に限定
        $isBuyer = $purchase->user_id === $user->id;
        $isSeller = $purchase->item && $purchase->item->user_id === $user->id;
        if (!($isBuyer || $isSeller)) {
            abort(403);
        }

        // 評価対象は相手ユーザー（自分以外）
        $rateeId = $isBuyer ? $purchase->item->user_id : $purchase->user_id;
        if ($rateeId === $user->id) {
            abort(403);
        }

        // 既に評価済みか確認（同一 rater, purchase）
        $existing = Rating::where('rater_id', $user->id)
            ->where('purchase_id', $purchase->id)
            ->first();

        if ($existing) {
            // 上書き（要件で明示されていないため、既存は上書きする）
            $existing->score = $data['score'];
            $existing->save();
        } else {
            Rating::create([
                'rater_id' => $user->id,
                'ratee_id' => $rateeId,
                'purchase_id' => $purchase->id,
                'score' => $data['score'],
            ]);
        }

        // 要件 FN014: 評価送信後は商品一覧画面へ遷移
        return redirect('/')->with('success', '評価を送信しました');
    }
}

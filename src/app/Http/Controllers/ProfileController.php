<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Http\Requests\ProfileRequest;
use App\Models\Purchase;
use App\Models\Message;


class ProfileController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        $profile = $user->profile;
        if (!$profile) {
            $profile = (object)[
                'image'     => null,
                'name'      => null,
                'post_code' => null,
                'address'   => null,
                'building'  => null,
            ];
        }

        // 購入した商品タブ選択時
        if ($request->query('page') === 'buy') {
            $items = Item::with(['purchase'])
                ->withCount('purchase')
                ->whereHas('purchase', fn($q) => $q->where('user_id', $user->id))
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->get();
        }
        // 出品した商品タブ選択時
        elseif ($request->query('page') === 'sell') {
            $items = Item::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->get();
        }
        // その他（デフォルト）
        else {
            $items = Item::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->get();
        }

        // 取引中タブ（page=trade）用に purchases を取得して未読数と最終メッセージ時刻を追加
        $tradePurchases = null;
        if ($request->query('page') === 'trade') {
            // ユーザーが関係する purchases（購入者 OR 出品者）を取得
            $tradePurchases = Purchase::with('item')
                ->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                        ->orWhereHas('item', function ($q2) use ($user) {
                            $q2->where('user_id', $user->id);
                        });
                })
                ->get()
                ->map(function ($purchase) use ($user) {
                    // 未読件数（自分以外の送信者からの未読）
                    $unread = Message::where('purchase_id', $purchase->id)
                        ->whereNull('read_at')
                        ->where('sender_id', '!=', $user->id)
                        ->count();
                    $purchase->unread_count = $unread;

                    // 最新メッセージ時刻（なければ purchase.updated_at を代わりに使う）
                    $purchase->last_message_at = Message::where('purchase_id', $purchase->id)
                        ->latest('created_at')
                        ->value('created_at') ?? $purchase->updated_at;

                    return $purchase;
                })
                // 新着順（最新メッセージがあるものを上）
                ->sortByDesc('last_message_at')
                ->values();
        }

        return view('profile.index', compact('profile', 'items', 'tradePurchases'));
    }

    public function edit()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
            $profile = (object)[
                'image'     => null,
                'name'      => null,
                'post_code' => null,
                'address'   => null,
                'building'  => null,
            ];
        }

        return view('profile.edit', compact('profile'));
    }
    public function update(ProfileRequest $request)
    {

        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $profile = $user->profile;


        if (!$profile) {
            $profile = $user->profile()->create([
                'image'     => null,
                'name'      => 'test',
                'post_code' => 'test',
                'address'   => 'test',
                'building'  => null,
            ]);
        }

        $data = ([
            'name' => $request->input('name'),
            'post_code' => $request->input('post_code'),
            'address' => $request->input('address'),
            'building' => $request->input('building'),

        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }

        $profile->update($data);

        return redirect()->route('mypage.index')->with('success', 'プロフィールを更新しました。');
    }
}

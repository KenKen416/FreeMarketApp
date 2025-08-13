<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;

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

        //購入した商品タブ選択時
        if (
            $request->query('page') === 'buy'
        ) {
            $items = Item::with(['purchase'])
                ->withCount('purchase')
                ->whereHas('purchase', fn($q) => $q->where('user_id', $user->id))
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->get();
        }


        //出品した商品タブ選択時
        else {
            $items = Item::withCount('purchase')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->get();
        }

        return view('profile.index', compact('items', 'profile'));
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

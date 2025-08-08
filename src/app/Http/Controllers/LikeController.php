<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store($item_id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->likes()->firstOrCreate([
            'item_id' => $item_id,
        ]);

        return back();
    }
    public function destroy($item_id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->likes()->where('item_id', $item_id)->delete();

        return back();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;
use App\Models\Like;

class ItemController extends Controller
{

    public function index(Request $request)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (
            $request->query('tab') === 'mylist'
        ) {
            if (! $user || ! $user->hasVerifiedEmail()) {
                return redirect()->route('login')->with('failed', 'ログインしてください。会員登録済みの方はメール認証をしてください');
            }

            $items = $user->likes()->with('item')->get()->pluck('item');
        } else {
            $items = Item::all();
        }
        return view('items.index', compact('items'));
    }


    public function show($id) {}

    public function create()
    {

        return view('items.create');
    }

    public function store(Request $request) {}
}

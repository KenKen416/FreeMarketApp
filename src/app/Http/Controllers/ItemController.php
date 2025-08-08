<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;
use App\Models\Like;
use App\Models\Condition;
use App\Models\Comment;

class ItemController extends Controller
{

    public function index(Request $request)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $query = Item::withCount('purchase')
            ->where('name', 'like', '%' . $request->query('keyword') . '%')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');
        if ($user) {
            $query = $query->where('user_id', '<>', $user->id);
        }
        //マイリストタブ選択時
        if (
            $request->query('tab') === 'mylist'
        ) {
            if ($user) {
                $likedIds = $user->likes()->pluck('item_id');
                $items = $query->whereIn('id', $likedIds)->get();
            } else {
                $items = collect();
            }

            //マイリストタブ未選択時
        } else {
            $items = $query->get();
        }
        return view('items.index', compact('items'));
    }


    public function show($item_id)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $item = Item::findOrFail($item_id);
        $item_condition = Condition::findOrFail($item->condition_id);
        $categories = $item->categories()->get();
        $likes_count = Like::where('item_id', $item->id)->count();
        if (!$user) {
            $likes_user_count = 0;
        } else {
            $likes_user_count = Like::where('item_id', $item->id)->where('user_id', $user->id)->count();
        }
        $comments_count = Comment::where('item_id', $item->id)->count();
        $comments = Comment::where('item_id', $item->id)->with('user.profile')->get();
        return view('items.show', compact('item', 'item_condition', 'categories', 'likes_count', 'likes_user_count', 'comments_count', 'comments'));
    }

    public function create()
    {

        return view('items.create');
    }

    public function store(Request $request) {}
}

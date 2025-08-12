<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;


class PurchaseController extends Controller
{
    public function create($item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        $post_code = $user->profile->post_code ?? '';
        $address = $user->profile->address ?? '';
        $building = $user->profile->building ?? '';

        return view('purchases.index', compact('item', 'post_code', 'address', 'building'));
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        $post_code = $request->post_code;
        $address = $request->address;
        $building = $request->building ?? '';

        return view('purchases.index', compact('item', 'post_code', 'address', 'building'));
    }


    public function editAddress($item_id)
    {

        return view('purchases.edit_address', compact('item_id'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        // 購入情報を登録する
        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'post_code' => $request->post_code,
            'address' => $request->address,
            'building' => $request->building,
            'payment_method' => $request->payment_method,
        ]);


        return redirect('/')->with('success', '購入が完了しました。');
    }

    public function success()
    {
        // 購入成功画面の表示
        return view('purchase.success');
    }
}

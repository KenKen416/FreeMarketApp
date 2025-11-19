<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use Stripe\Stripe;
use Stripe\StripeClient;

class PurchaseController extends Controller
{
    public function create($item_id)
    {
        $user = Auth::user();
        $item = Item::withCount('purchase')->findOrFail($item_id);

        $post_code = $user->profile->post_code ?? '';
        $address = $user->profile->address ?? '';
        $building = $user->profile->building ?? '';

        return view('purchases.index', compact('item', 'post_code', 'address', 'building'));
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        $user = Auth::user();
        $item = Item::withCount('purchase')->findOrFail($item_id);

        $post_code = $request->post_code;
        $address = $request->address;
        $building = $request->building ?? '';

        return view('purchases.index', compact('item', 'post_code', 'address', 'building'));
    }


    public function editAddress($item_id)
    {
        $user = Auth::user();

        $post_code = $user->profile->post_code ?? '';
        $address = $user->profile->address ?? '';
        $building = $user->profile->building ?? '';

        return view('purchases.edit_address', compact('item_id', 'post_code', 'address', 'building'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        if ($request->payment_method === 'konbini') {
            Purchase::create([
                'user_id'        => $user->id,
                'item_id'        => $item->id,
                'post_code'      => $request->post_code,
                'address'        => $request->address,
                'building'       => $request->building,
                'payment_method' => 'konbini',
                'status'         => 'pending',
            ]);
            return redirect()->route('purchases.complete.index')
                ->with('success', 'コンビニ払いの受付を完了しました。');
        }

        $stripe = new StripeClient(config('services.stripe.secret'));

        $session = $stripe->checkout->sessions->create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $item->name],
                    'unit_amount'  => $item->price,
                ],
                'quantity' => 1,
            ]],
            'success_url'    => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'     => route('checkout.cancel'),
            'customer_email' => $user->email,
            'metadata' => [
                'user_id'   => $user->id,
                'item_id'   => $item->id,
                'post_code' => $request->post_code,
                'address'   => $request->address,
                'building'  => $request->building ?? '',
            ],
        ]);

        return redirect()->away($session->url, 303);

    }


    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');
        if (!$sessionId) return view('checkout.success');

        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        $session = $stripe->checkout->sessions->retrieve($sessionId, []);

        if (!$session->payment_intent) {
            return view('checkout.success', compact('sessionId')); 
        }
        $pi = $stripe->paymentIntents->retrieve($session->payment_intent, []);

        if ($pi->status !== 'succeeded') {
            return view('checkout.success', compact('sessionId'));
        }

        $md = $session->metadata ?? new \stdClass();

        $exists = Purchase::where('item_id', $md->item_id ?? 0)
            ->where('payment_method', 'card')
            ->where('status', 'paid')
            ->exists();

        if (!$exists) {
            \App\Models\Purchase::create([
                'user_id'        => $md->user_id,
                'item_id'        => $md->item_id,
                'post_code'      => $md->post_code,
                'address'        => $md->address,
                'building'       => $md->building,
                'payment_method' => 'card',
                'status'         => 'paid',
                'stripe_session_id'        => $session->id,
                'stripe_payment_intent_id' => $pi->id,
            ]);
        }

        return view('checkout.success', compact('sessionId'));
    }
    public function cancel()
    {
        return view('checkout.cancel');
    }

    public function complete()
    {
        return view('purchases.complete');
    }
}

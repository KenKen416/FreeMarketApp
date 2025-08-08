<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [ItemController::class, 'index'])->name('items.index');

Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');







Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage/profile', function () {
        $user = (object) [
            'name' => 'John Doe',
            'email' => '',
            'image' => null,
            'post_code' => null
        ];
        return view('profile.edit', compact('user'));
    })->name('profile.edit');
    Route::get('/sell', function () {
        return view('items.create');
    })->name('items.create');

    Route::get('/purchase/address/{item_id}', function () {
        return view('purchases.edit_address');
    })->name('purchases.edit_address');



    Route::get('/purchase/{item_id}', function () {
        $item = (object) [
            'id' => 1,
            'name' => 'オレンジ３個',
            'image' => 'orange.png',
            'description' => '美味しいオレンジです.',
            'price' => 1000,
            'brand_name' => 'オレンジ農園',
            'item_condition' => '新品',
            'categories' => ['食べ物', '果物', '食べ物', '果物', '食べ物', '果物', '食べ物', '果物'],
        ];
        return view('purchases.index', compact('item'));
    })->name('purchases.index');


    Route::post('/item/{item_id}/like', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/item/{item_id}/like', [LikeController::class, 'destroy'])->name('likes.destroy');

    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('comments.store');
});

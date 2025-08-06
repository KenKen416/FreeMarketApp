<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('items.index');
});
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
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

Route::get('/item/{item_id}', function () {
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
    return view('items.show', compact('item'));
})->name('items.show');

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

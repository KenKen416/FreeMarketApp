<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        $items = [
            ['id' => 1, 'user_id' => 1, 'name' => '腕時計',         'image' => 'images/Watch.jpg',      'condition_id' => 1, 'price' => 15000, 'brand_name' => 'Rolax',     'description' => 'スタイリッシュなデザインのメンズ腕時計'],
            ['id' => 2, 'user_id' => 2, 'name' => 'HDD',            'image' => 'images/HDD.jpg',        'condition_id' => 2, 'price' => 5000,  'brand_name' => '西芝',       'description' => '高速で信頼性の高いハードディスク'],
            ['id' => 3, 'user_id' => 3, 'name' => '玉ねぎ3束',       'image' => 'images/Onion.jpg',      'condition_id' => 3, 'price' => 300,   'brand_name' => 'なし',       'description' => '新鮮な玉ねぎ3束のセット'],
            ['id' => 4, 'user_id' => 1, 'name' => '革靴',           'image' => 'images/Shoes.jpg',      'condition_id' => 4, 'price' => 4000,  'brand_name' => '',           'description' => 'クラシックなデザインの革靴'],
            ['id' => 5, 'user_id' => 2, 'name' => 'ノートPC',        'image' => 'images/Laptop.jpg',     'condition_id' => 1, 'price' => 45000, 'brand_name' => '',           'description' => '高性能なノートパソコン'],
            ['id' => 6, 'user_id' => 3, 'name' => 'マイク',          'image' => 'images/Mic.jpg',        'condition_id' => 2, 'price' => 8000,  'brand_name' => 'なし',       'description' => '高音質のレコーディング用マイク'],
            ['id' => 7, 'user_id' => 1, 'name' => 'ショルダーバッグ', 'image' => 'images/Bag.jpg',        'condition_id' => 3, 'price' => 3500,  'brand_name' => '',           'description' => 'おしゃれなショルダーバッグ'],
            ['id' => 8, 'user_id' => 2, 'name' => 'タンブラー',       'image' => 'images/Tumbler.jpg',    'condition_id' => 4, 'price' => 500,   'brand_name' => 'なし',       'description' => '使いやすいタンブラー'],
            ['id' => 9, 'user_id' => 3, 'name' => 'コーヒーミル',     'image' => 'images/Grinder.jpg', 'condition_id' => 1, 'price' => 4000,  'brand_name' => 'Starbacks',  'description' => '手動のコーヒーミル'],
            ['id' => 10, 'user_id' => 1, 'name' => 'メイクセット',    'image' => 'images/Cosme.jpg',     'condition_id' => 2, 'price' => 2500,  'brand_name' => '',           'description' => '便利なメイクアップセット'],
        ];

        // items 本体を insert
        foreach ($items as $i) {
            $brand = trim((string)($i['brand_name'] ?? ''));
            $brandOrNull = ($brand === '' || $brand === 'なし') ? null : $brand;

            DB::table('items')->insert([
                'id'           => $i['id'],
                'user_id'      => $i['user_id'],
                'name'         => $i['name'],
                'image'        => $i['image'],
                'condition_id' => $i['condition_id'],
                'brand_name'   => $brandOrNull,
                'description'  => $i['description'],
                'price'        => $i['price'],
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }
    }
}

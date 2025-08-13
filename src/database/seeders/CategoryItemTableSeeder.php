<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoryItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        $rows = [
            // 1: 腕時計 → ファッション(1), メンズ(5), アクセサリー(12)
            ['item_id' => 1,  'category_id' => 1],
            ['item_id' => 1,  'category_id' => 5],
            ['item_id' => 1,  'category_id' => 12],

            // 2: HDD → 家電(2)
            ['item_id' => 2,  'category_id' => 2],

            // 3: 玉ねぎ3束 → キッチン(10)
            ['item_id' => 3,  'category_id' => 10],

            // 4: 革靴 → ファッション(1), メンズ(5)
            ['item_id' => 4,  'category_id' => 1],
            ['item_id' => 4,  'category_id' => 5],

            // 5: ノートPC → 家電(2)
            ['item_id' => 5,  'category_id' => 2],

            // 6: マイク → 家電(2)
            ['item_id' => 6,  'category_id' => 2],

            // 7: ショルダーバッグ → ファッション(1), レディース(4)
            ['item_id' => 7,  'category_id' => 1],
            ['item_id' => 7,  'category_id' => 4],

            // 8: タンブラー → キッチン(10)
            ['item_id' => 8,  'category_id' => 10],

            // 9: コーヒーミル → 家電(2), キッチン(10)
            ['item_id' => 9,  'category_id' => 2],
            ['item_id' => 9,  'category_id' => 10],

            // 10: メイクセット → レディース(4), ファッション(1)
            ['item_id' => 10, 'category_id' => 4],
            ['item_id' => 10, 'category_id' => 1],
        ];

        foreach ($rows as $r) {
            DB::table('category_item')->insert([
                'item_id' => $r['item_id'],
                'category_id' => $r['category_id'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}

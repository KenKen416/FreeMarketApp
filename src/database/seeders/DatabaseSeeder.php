<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 外部キー制約を無効化（MySQLの場合）
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        //  全テーブルを空にする
        DB::table('category_item')->truncate();
        DB::table('comments')->truncate();
        DB::table('likes')->truncate();
        DB::table('purchases')->truncate();
        DB::table('items')->truncate();
        DB::table('profiles')->truncate();
        DB::table('categories')->truncate();
        DB::table('conditions')->truncate();
        DB::table('users')->truncate();

        // 外部キー制約を再有効化
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        //  ここからSeeder実行
        $this->call(UsersTableSeeder::class);
        $this->call(ConditionsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(ItemsTableSeeder::class);
        $this->call(CategoryItemTableSeeder::class);
        // 他に必要なSeederがあれば追加
    }
}

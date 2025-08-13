<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ConditionsTableSeeder extends Seeder
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
            ['id' => 1, 'name' => '良好'],
            ['id' => 2, 'name' => '目立った傷や汚れなし'],
            ['id' => 3, 'name' => 'やや傷や汚れあり'],
            ['id' => 4, 'name' => '状態が悪い'],
        ];
        foreach ($rows as $r) {
            DB::table('conditions')->insert([
                'id' => $r['id'],
                'name' => $r['name'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}

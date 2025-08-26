<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Condition;
use App\Models\User;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $conditionId = Condition::query()->inRandomOrder()->value('id');
        static $fruits = [
            'りんご',
            'みかん',
            'バナナ',
            'ぶどう',
            'いちご',
            '梨',
            '柿',
            '桃',
            'スイカ',
            'メロン',
            'さくらんぼ',
            'キウイ',
            'パイナップル',
            'マンゴー',
            'グレープフルーツ',
            'レモン',
            'ライム',
            'ブルーベリー',
            'ラズベリー',
            'ブラックベリー',
            'ドラゴンフルーツ',
            'パパイヤ',
            'パッションフルーツ',
            'すだち',
            'ゆず',
            'かぼす',
            'びわ',
            'いちじく',
            'ざくろ',
            'アボカド',
            '栗'
        ];

        $name = $this->faker->randomElement($fruits);
        return [
            'user_id' => User::factory(),
            'name' => $name,
            'image' => 'images/sample.png',
            'condition_id' => $conditionId,
            'brand_name' => $this->faker->company(),
            'description' => $this->faker->realText(180),
            'price' => $this->faker->numberBetween(1000, 100000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

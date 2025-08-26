<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use App\Models\Item;
use App\Models\User;

class ItemCreateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
    }
    //商品出品画面にて必要な情報が保存できること（カテゴリ、商品の状態、商品名、ブランド名、商品の説明、販売価格）
    public function test_item_create_user_can_store_successfully(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $itemData = [
            'name' => 'Test Item',
            'brand_name' => 'Test Brand',
            'price' => 3333,
            'description' => 'Test Description',
            'condition_id' => 1,
            'categories' => [1, 2],
        ];

        $response = $this->post(route('items.store'), $itemData);

        $response->assertStatus(302);
        $response->assertRedirect(route('items.index'));

        $this->assertDatabaseHas('items', [
            'name' => $itemData['name'],
            'brand_name' => $itemData['brand_name'],
            'price' => $itemData['price'],
            'description' => $itemData['description'],
            'condition_id' => $itemData['condition_id'],
        ]);

        $item = Item::where('name', $itemData['name'])->first();
        $this->assertCount(2, $item->categories);
    }
}

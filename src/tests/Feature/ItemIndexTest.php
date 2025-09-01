<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Purchase;
use Database\Seeders\ConditionsTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ItemIndexTest extends TestCase
{
    use RefreshDatabase;
    private const URL = '/';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionsTableSeeder::class);
    }

    //全商品を取得できる
    public function test_item_index_pageload_success(): void
    {
        $items = Item::factory()->count(5)->create();


        $response = $this->get(self::URL);
        $response->assertStatus(200);

        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    //購入済みの商品は「sold」と表示される
    public function test_item_index_display_sold(): void
    {
        $notSoldItem = Item::factory()->create();
        $soldItem = Item::factory()->create();
        $response = $this->get(self::URL);
        $response->assertStatus(200);
        $response->assertDontSeeText('sold');

        Purchase::factory()->create(['item_id' => $soldItem->id]);

        $response = $this->get(self::URL);
        $response->assertStatus(200);

        $response->assertSeeText('sold');
    }
    //自分が出品した商品は表示されない
    public function test_item_index_does_not_display_own_items(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $ownItem = Item::factory()->create(['user_id' => $user->id]);
        $notOwnItem = Item::factory()->create(['user_id' => User::factory()->create()->id]);

        $this->actingAs($user);
        $response = $this->get(self::URL);
        $response->assertStatus(200);

        $response->assertDontSee($ownItem->name);
        $response->assertSee($notOwnItem->name);
    }
}

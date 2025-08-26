<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\ConditionsTableSeeder;
use App\Models\Item;
use App\Models\Like;
use App\Models\User;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionsTableSeeder::class);
    }

    //商品名で部分一致検索ができる
    public function test_item_search_by_name_partial_match(): void
    {
        $hitItem1 = Item::factory()->create(['name' => '赤りんご']);
        $hitItem2 = Item::factory()->create(['name' => '青りんご']);
        $notHitItem1 = Item::factory()->create(['name' => 'バナナ']);

        $keyword = 'りんご';

        $response = $this->get(route('items.index', ['keyword' => $keyword]));

        $response->assertStatus(200);
        $response->assertSeeText($hitItem1->name);
        $response->assertSeeText($hitItem2->name);
        $response->assertDontSeeText($notHitItem1->name);
    }
    //検索状態がマイリストでも保持されている
    public function test_item_search_state_retained_in_mylist(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $hitLikedItem = Item::factory()->create(['name' => '赤りんご']);
        $hitNotLikedItem = Item::factory()->create(['name' => '青りんご']);
        $notHitItem = Item::factory()->create(['name' => 'バナナ']);
        Like::factory()->create([
            'item_id' => $hitLikedItem->id,
            'user_id' => $user->id
        ]);

        $keyword = 'りんご';

        $this->actingAs($user);

        $response = $this->from('/')->get(route('items.index', ['keyword' => $keyword]));

        $response->assertStatus(200);
        $response->assertSeeText($hitLikedItem->name);
        $response->assertSeeText($hitNotLikedItem->name);
        $response->assertDontSeeText($notHitItem->name);

        // マイリストタブに遷移
        $response = $this->get(route('items.index', ['tab' => 'mylist', 'keyword' => $keyword]));
        $response->assertStatus(200);
        $response->assertSeeText($hitLikedItem->name);
        $response->assertDontSeeText($hitNotLikedItem->name);
        $response->assertDontSeeText($notHitItem->name);
    }
}

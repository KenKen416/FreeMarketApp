<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\ConditionsTableSeeder;
use App\Models\Item;
use App\Models\User;
use App\Models\Like;
use App\Models\Purchase;

class MylistTest extends TestCase
{
    use RefreshDatabase;
    private const URL = '/?tab=mylist';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionsTableSeeder::class);
    }

    //いいねした商品だけが表示される
    public function test_mylist_shows_only_liked_items(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $notLikedItem = Item::factory()->create(['name' => 'Not Liked Item']);
        $otherUserLikedItem = Item::factory()->create(['name' => 'Other User Liked Item']);
        $likedItem = Item::factory()->create(['name' => 'Liked Item']);
        Like::factory()->create([
            'user_id' => $otherUser->id,
            'item_id' => $otherUserLikedItem->id,
        ]);
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $this->actingAs($user);
        $response = $this->get(self::URL);
        $response->assertStatus(200);

        $response->assertSee($likedItem->name);
        $response->assertDontSee($otherUserLikedItem->name);
        $response->assertDontSee($notLikedItem->name);
    }

    //購入済み商品は「sold」と表示される
    public function test_mylist_shows_sold_items_as_sold(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $soldItem = Item::factory()->create();
        $notSoldItem = Item::factory()->create();

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $soldItem->id,
        ]);
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $notSoldItem->id,
        ]);

        //まだ購入されていない状態で確認
        $this->actingAs($user);
        $response = $this->get(self::URL);
        $response->assertStatus(200);
        $response->assertDontSeeText('sold');

        //購入済み状態の商品を作成
        Purchase::factory()->create([
            'item_id' => $soldItem->id,
        ]);

        $this->actingAs($user);
        $response = $this->get(self::URL);
        $response->assertStatus(200);
        $response->assertSeeText('sold');
    }
    //未認証の場合、商品は表示されない
    public function test_mylist_not_authenticated_user_does_not_see_items(): void
    {
        $notLikedItem = Item::factory()->create();
        $likedItem = Item::factory()->create();
        Like::factory()->create();

        $response = $this->get(self::URL);
        $this->assertGuest();
        $response->assertStatus(200);

        $response->assertDontSee($notLikedItem->name);
        $response->assertDontSee($likedItem->name);
        $response->assertSeeText('商品が見つかりませんでした。');
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\ConditionsTableSeeder;
use App\Models\Item;
use App\Models\User;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionsTableSeeder::class);
    }
    //「購入する」ボタンを押下すると購入が完了する
    public function test_purchase_completes_successfully()
    {
        $user = User::factory()->create();
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $item = Item::factory()->create();


        $purchaseData = [
            'payment_method' => 'konbini',
            'post_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => '渋谷マンション101号室',
        ];

        $response = $this->followingRedirects()
            ->from(route('purchases.index', ['item_id' => $item->id]))
            ->post(route('purchases.store', ['item_id' => $item->id]), $purchaseData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);
    }

    //購入した商品は商品一覧画面にて「sold」と表示される
    public function test_purchase_purchased_item_display_sold(): void
    {

        $user = User::factory()->create();
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $notSoldItem = Item::factory()->create();
        $soldItem = Item::factory()->create();

        $response = $this->get(route('items.index'));
        $response->assertStatus(200);
        $response->assertDontSeeText('sold');

        $purchaseData = [
            'payment_method' => 'konbini',
            'post_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => '渋谷マンション101号室',
        ];

        $response = $this->followingRedirects()
            ->from(route('purchases.index', ['item_id' => $soldItem->id]))
            ->post(route('purchases.store', ['item_id' => $soldItem->id]), $purchaseData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('purchases', [
            'item_id' => $soldItem->id,
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('items.index'));
        $response->assertStatus(200);

        $response->assertSeeText('sold');
    }
    //「プロフィール/購入した商品一覧」に追加されている
    public function test_purchase_purchased_item_display_in_profile(): void
    {
        $user = User::factory()->create();
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $item = Item::factory()->create();
        $purchaseData = [
            'payment_method' => 'konbini',
            'post_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => '渋谷マンション101号室',
        ];

        $response = $this->get(route('mypage.index') . '?page=buy');
        $response->assertStatus(200);
        $response->assertDontSeeText($item->name);



        $response = $this->followingRedirects()
            ->from(route('purchases.index', ['item_id' => $item->id]))
            ->post(route('purchases.store', ['item_id' => $item->id]), $purchaseData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('mypage.index') . '?page=buy');
        $response->assertStatus(200);
        $response->assertSeeText($item->name);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;

class AddressChangeTest extends TestCase
{


    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
    }
    //送付先住所変更画面にて登録した住所が商品購入画面に反映されている
    public function test_address_change_reflects_in_purchase_page(): void
    {
        $user = \App\Models\User::factory()->create();
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $item = \App\Models\Item::factory()->create();

        $addressData = [
            'post_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => '渋谷マンション101号室',
        ];

        $response = $this->get(route('purchases.index', ['item_id' => $item->id]));
        $response->assertStatus(200)
            ->assertViewIs('purchases.index')
            ->assertDontSeeText($addressData['post_code'])
            ->assertDontSeeText($addressData['address'])
            ->assertDontSeeText($addressData['building']);

        $response = $this->from(route('purchases.edit_address', ['item_id' => $item->id]))
            ->post(route('update.address', ['item_id' => $item->id]), $addressData);
        $response->assertStatus(200)
            ->assertViewIs('purchases.index')
            ->assertSeeText($addressData['post_code'])
            ->assertSeeText($addressData['address'])
            ->assertSeeText($addressData['building']);
    }

    //購入した商品に送付先住所が紐づいて登録される
    public function test_address_change_store_successfully(): void
    {
        $user = \App\Models\User::factory()->create();
        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $item = \App\Models\Item::factory()->create();

        $addressData = [
            'post_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => '渋谷マンション101号室',
        ];
        $purchaseData = [
            'payment_method' => 'konbini',
            'post_code' => $addressData['post_code'],
            'address' => $addressData['address'],
            'building' => $addressData['building'],
        ];

        $response = $this->from(route('purchases.edit_address', ['item_id' => $item->id]))
            ->post(route('update.address', ['item_id' => $item->id]), $addressData);

        $response = $this->followingRedirects()
            ->post(route('purchases.store', ['item_id' => $item->id]), $purchaseData);
        $response->assertStatus(200);
        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'post_code' => $addressData['post_code'],
            'address' => $addressData['address'],
            'building' => $addressData['building'],
        ]);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use App\Models\Item;
use App\Models\User;
use App\Models\Profile;
use App\Models\Purchase;

class UserInfoGetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
    }


    //必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧）
    public function test_user_info_get_successfully(): void
    {
        $user = User::factory()->create();
        /** @var User $user */
        $this->actingAs($user);

        $purchasedItem = Item::factory()->create();
        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $purchasedItem->id,
        ]);
        $exhibitedItem = Item::factory()->create(['user_id' => $user->id]);
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('mypage.index'));
        $response->assertStatus(200)
            ->assertSee('storage/' . $profile->image)
            ->assertSeeText($profile->name)
            ->assertSeeText($exhibitedItem->name);

        $response = $this->get(route('mypage.index') . '?page=buy');
        $response->assertStatus(200)
            ->assertSeeText($purchasedItem->name);
    }
}

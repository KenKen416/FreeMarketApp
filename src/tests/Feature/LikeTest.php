<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use App\Models\Item;
use App\Models\User;
use App\Models\Comment;


class LikeTest extends TestCase
{
    use RefreshDatabase;
    private const NORMAL_STAR = 'images/star.png';
    private const COLOR_STAR = 'images/star_color.png';


    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
    }

    private function createItem(): Item
    {
        return Item::factory()->create([
            'name' => 'Test Item',
            'brand_name' => 'Test Brand',
            'price' => 3333,
            'description' => 'Test Description'
        ]);
    }


    //いいねアイコンを押下することによって、いいねした商品として登録することができる
    public function test_like_user_can_like(): void
    {
        $item = $this->createItem();
        $user = User::factory()->create();
        Comment::factory()->count(3)->create(['item_id' => $item->id]);

        /** @var \App\Models\User $user */

        $this->actingAs($user)
            ->get(route('items.show', ['item_id' => $item->id]))
            ->assertStatus(200)
            ->assertSeeText('0')
            ->assertDontSeeText('1');

        $this->assertDatabaseMissing('likes', ['item_id' => $item->id, 'user_id' => $user->id]);


        $this->post(route('likes.store', ['item_id' => $item->id]));

        $this->assertDatabaseHas('likes', ['item_id' => $item->id, 'user_id' => $user->id]);

        $this->get(route('items.show', ['item_id' => $item->id]))
            ->assertStatus(200)
            ->assertSeeText('1')
            ->assertDontSeeText('0');
    }
    //追加済みのアイコンは色が変化する
    public function test_like_user_can_see_colored_star_after_liking(): void
    {
        $item = $this->createItem();
        $user = User::factory()->create();

        /** @var \App\Models\User $user */
        $this->actingAs($user)
            ->get(route('items.show', ['item_id' => $item->id]))
            ->assertStatus(200)
            ->assertSee(self::NORMAL_STAR)
            ->assertDontSee(self::COLOR_STAR);


        $this->post(route('likes.store', ['item_id' => $item->id]));

        $this->assertDatabaseHas('likes', ['item_id' => $item->id, 'user_id' => $user->id]);

        $this->get(route('items.show', ['item_id' => $item->id]))
            ->assertStatus(200)
            ->assertSee(self::COLOR_STAR)
            ->assertDontSee(self::NORMAL_STAR);
    }


    //再度いいねアイコンを押下することによって、いいねを解除することができる
    public function test_like_user_can_unlike(): void
    {
        $item = $this->createItem();
        $user = User::factory()->create();
        Comment::factory()->count(3)->create(['item_id' => $item->id]); //いいねの数字を把握（０と１の数値の変化を監視対象）するときに、コメント数も間違って監視しちゃうとことを避けるために、コメントを3つ作成

        /** @var \App\Models\User $user */

        $this->actingAs($user)
            ->get(route('items.show', ['item_id' => $item->id]))
            ->assertStatus(200)
            ->assertSeeText('0')
            ->assertDontSeeText('1');

        $this->post(route('likes.store', ['item_id' => $item->id]));

        $this->assertDatabaseHas('likes', ['item_id' => $item->id, 'user_id' => $user->id]);

        $this->get(route('items.show', ['item_id' => $item->id]))
            ->assertStatus(200)
            ->assertSeeText('1')
            ->assertDontSeeText('0');

        $this->delete(route('likes.destroy', ['item_id' => $item->id]));

        $this->assertDatabaseMissing('likes', ['item_id' => $item->id, 'user_id' => $user->id]);

        $this->get(route('items.show', ['item_id' => $item->id]))
            ->assertStatus(200)
            ->assertSeeText('0')
            ->assertDontSeeText('1');
    }
}

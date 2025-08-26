<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\ConditionsTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;
use App\Models\User;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    private const COMMENT_REQUIRED = 'コメントは必須です。';
    private const COMMENT_MAX = 'コメントは255文字以内で入力してください。';

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
    private function createLike(Item $item)
    {
        return Like::factory()->count(3)->create([
            'item_id' => $item->id,
        ]);
    }


    //ログイン済みのユーザーはコメントを送信できる
    public function test_comment_user_can_comment(): void
    {
        $item = $this->createItem();
        $this->createLike($item);
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->get(route('items.show', ['item_id' => $item->id]))
            ->assertStatus(200)
            ->assertSeeText(0)
            ->assertDontSeeText(1)
            ->assertDontSeeText('Test Comment');
        $this->assertDatabaseMissing('comments', ['item_id' => $item->id]);

        $this->post(route('comments.store', ['item_id' => $item->id, 'comment' => 'Test Comment']));

        $this->get(route('items.show', ['item_id' => $item->id]))
            ->assertStatus(200)
            ->assertSeeText(1)
            ->assertDontSeeText(0)
            ->assertSeeText('Test Comment');
        $this->assertDatabaseHas('comments', ['item_id' => $item->id]);
    }

    //ログイン前のユーザーはコメントを送信できない
    public function test_comment_user_cannot_comment_without_login(): void
    {
        $item = $this->createItem();

        $this->get(route('items.show', ['item_id' => $item->id]))
            ->assertStatus(200);

        $this->post(route('comments.store', ['item_id' => $item->id]), ['comment' => 'Test Comment'])
            ->assertRedirect(route('login'));

        $this->assertDatabaseMissing('comments', ['item_id' => $item->id]);

        $this->get(route('items.show', ['item_id' => $item->id]))
            ->assertStatus(200)
            ->assertDontSeeText('Test Comment');
    }

    //コメントが入力されていない場合、バリデーションメッセージが表示される
    public function test_comment_validation_error_when_empty(): void
    {
        $item = $this->createItem();
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get(route('items.show', ['item_id' => $item->id]))
            ->assertStatus(200);

        $response = $this->followingRedirects()->post(route('comments.store', ['item_id' => $item->id]), ['comment' => '']);

        $response->assertSeeText(self::COMMENT_REQUIRED);

        $this->assertDatabaseMissing('comments', ['item_id' => $item->id]);
    }

    //コメントが255文字以上の場合、バリデーションメッセージが表示される
    public function test_comment_validation_error_when_too_long(): void
    {
        $item = $this->createItem();
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get(route('items.show', ['item_id' => $item->id]))
            ->assertStatus(200);

        $response = $this->followingRedirects()->post(route('comments.store', ['item_id' => $item->id]), ['comment' => str_repeat('a', 256)]);
        $response->assertSeeText(self::COMMENT_MAX);

        $this->assertDatabaseMissing('comments', ['item_id' => $item->id]);
    }
}

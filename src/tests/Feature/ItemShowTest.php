<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\ConditionsTableSeeder;
use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;
use App\Models\User;
use App\Models\Profile;
use Database\Seeders\CategoriesTableSeeder;

class ItemShowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ConditionsTableSeeder::class);
        $this->seed(CategoriesTableSeeder::class);
    }
//必要な情報が表示される
    public function test_item_show_page_loads_successfully(): void
    {
        $item = Item::factory()->create();
        $item->categories()->attach([1]);
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Profile::factory()->create(['user_id' => $user1->id]);
        Profile::factory()->create(['user_id' => $user2->id]);
        Like::factory()->count(3)->create(['item_id' => $item->id]);
        Comment::factory()->create(['item_id' => $item->id, 'user_id' => $user1->id,'comment'=>'コメント１']);
        Comment::factory()->create(['item_id' => $item->id, 'user_id' => $user2->id,'comment'=>'コメント２']);
        $likesCount = $item->likes->count();
        $commentsCount = $item->comments->count();


        $response = $this->get(route('items.show', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSee('storage/'. $item->image);
        $response->assertSeeText($item->name);
        $response->assertSeeText($item->brand_name);
        $response->assertSeeText(number_format($item->price));
        $response->assertSeeText($likesCount);
        $response->assertSeeText($commentsCount);
        $response->assertSeeText($item->description);
        $response->assertSeeText($item->categories->first()->name);
        $response->assertSeeText($item->condition->name);
        $response->assertSeeText($user1->profile->name);
        $response->assertSeeText($user2->profile->name);
        $response->assertSeeText('コメント１');
        $response->assertSeeText('コメント２');
    }

    //複数選択されたカテゴリが表示されているか
    public function test_item_show_page_displays_selected_categories(): void
    {
        $item = Item::factory()->create();
        $item->categories()->attach([1, 2]);

        $response = $this->get(route('items.show', ['item_id' => $item->id]));
        $response->assertStatus(200);

        $response->assertSeeText($item->categories[0]->name);
        $response->assertSeeText($item->categories[1]->name);
    }
}

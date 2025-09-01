<?php

namespace Tests\Feature;

use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserInfoChangeTest extends TestCase
{
    use RefreshDatabase;

    //変更項目が初期値として過去設定されていること（プロフィール画像、ユーザー名、郵便番号、住所）
    public function test_user_info_change_page_loads_successfully(): void
    {
        $user = User::factory()->create();
        /** @var User $user */
        $this->actingAs($user);
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'image' => 'images/profile_sample.png',
            'name' => '初期氏名',
            'post_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => '渋谷マンション101号室',
        ]);
        $response = $this->get(route('profile.edit'));
        $response->assertStatus(200)
            ->assertSee('storage/' . $profile->image)
            ->assertSee($profile->name)
            ->assertSee($profile->post_code)
            ->assertSee($profile->address)
            ->assertSee($profile->building);

        Storage::fake('public');
        $file = UploadedFile::fake()->image('profile_sample2.png');
        $updateData = [
            'image' => $file,
            'name' => '変更後氏名',
            'post_code' => '987-6543',
            'address' => '大阪府大阪市2-2-2',
            'building' => '梅田マンション202号室',
        ];
        $response = $this->followingRedirects()
            ->put(route('profile.update'), $updateData);
        $response->assertStatus(200);
        $updatedProfile = Profile::where('user_id', $user->id)->first();
        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'image' => $updatedProfile->image,
            'name' => $updateData['name'],
            'post_code' => $updateData['post_code'],
            'address' => $updateData['address'],
            'building' => $updateData['building'],
        ]);

        $response = $this->get(route('profile.edit'));
        $response->assertStatus(200)
            ->assertSee('storage/' . $updatedProfile->image)
            ->assertSee($updateData['name'])
            ->assertSee($updateData['post_code'])
            ->assertSee($updateData['address'])
            ->assertSee($updateData['building'])
            ->assertDontSee('storage/' . $profile->image)
            ->assertDontSee($profile->name)
            ->assertDontSee($profile->post_code)
            ->assertDontSee($profile->address)
            ->assertDontSee($profile->building);
    }
}

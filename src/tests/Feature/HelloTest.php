<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class HelloTest extends TestCase
{
    use RefreshDatabase; // ← テストごとにDBを初期化
    /** @test */
    public function DBにユーザーが作れる(): void
    {
        User::factory()->create(['name' => 'aaa', 'email' => 'bbb@ccc.com']);
        $this->assertDatabaseHas('users', ['email' => 'bbb@ccc.com']);
    }
}

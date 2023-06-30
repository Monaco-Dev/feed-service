<?php

namespace Tests\Feature\Post;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Post;
use App\Models\User;

class SearchTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $this->WithoutMiddleware();

        $user = User::factory()->make();
        Post::factory()->create(['user_id' => $this->faker()->unique()->randomDigit()]);

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('posts.search'))
            ->assertStatus(200);
    }

    /**
     * Test unauthenticated response.
     */
    public function test_unauthenticated(): void
    {
        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route('posts.search'))
            ->assertStatus(401);
    }
}

<?php

namespace Tests\Feature\Post;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Post;
use App\Models\User;

class StoreTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $this->WithoutMiddleware();

        $post = Post::factory()->make();
        $user = User::factory()->make();

        $payload = [
            'content' => $post->content
        ];

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('posts.store'), $payload)
            ->assertStatus(201);
    }

    /**
     * Test invalid response.
     */
    public function test_invalid(): void
    {
        $this->WithoutMiddleware();

        $user = User::factory()->make();

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('posts.store'))
            ->assertStatus(422);
    }

    /**
     * Test unauthenticated response.
     */
    public function test_unauthenticated(): void
    {
        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route('posts.store'))
            ->assertStatus(401);
    }
}

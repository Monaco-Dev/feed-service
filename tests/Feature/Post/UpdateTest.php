<?php

namespace Tests\Feature\Post;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Post;
use App\Models\User;

class UpdateTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $this->WithoutMiddleware();

        $user = User::factory()->make();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $dummy = Post::factory()->make();

        $payload = [
            'content' => $dummy->content
        ];

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->put(route('posts.update', $post->id), $payload)
            ->assertStatus(200);
    }

    /**
     * Test invalid response.
     */
    public function test_invalid(): void
    {
        $this->WithoutMiddleware();

        $user = User::factory()->make();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $payload = [
            'content' => null
        ];

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->put(route('posts.update', $post->id), $payload)
            ->assertStatus(422);
    }

    /**
     * Test unauthenticated response.
     */
    public function test_unauthenticated(): void
    {
        $post = Post::factory()->create();

        $this->withHeaders(['Accept' => 'application/json'])
            ->put(route('posts.update', $post->id))
            ->assertStatus(401);
    }

    /**
     * Test not found response.
     */
    public function test_not_found(): void
    {
        $this->WithoutMiddleware();

        $this->withHeaders(['Accept' => 'application/json'])
            ->put(route('posts.update', 0))
            ->assertStatus(404);
    }

    /**
     * Test unauthorized response.
     */
    public function test_unauthorized(): void
    {
        $this->WithoutMiddleware();

        $user = User::factory()->make();
        $post = Post::factory()->create(['user_id' => 0]);

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->put(route('posts.update', $post->id))
            ->assertStatus(403);
    }
}

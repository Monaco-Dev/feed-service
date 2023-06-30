<?php

namespace Tests\Feature\Share;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Post;
use App\Models\Share;
use App\Models\User;

class DestroyTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $this->WithoutMiddleware();

        $user = User::factory()->make();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $share = Share::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->delete(route('shares.destroy', $share->id))
            ->assertStatus(200);
    }

    /**
     * Test unauthorized response.
     */
    public function test_unauthorized(): void
    {
        $this->WithoutMiddleware();

        $user = User::factory()->make();
        $post = Post::factory()->create(['user_id' => $user->id + 1]);
        $share = Share::factory()->create(['post_id' => $post->id, 'user_id' => $user->id + 1]);

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->delete(route('shares.destroy', $share->id))
            ->assertStatus(403);
    }

    /**
     * Test not found response.
     */
    public function test_not_found(): void
    {
        $this->WithoutMiddleware();

        $this->withHeaders(['Accept' => 'application/json'])
            ->delete(route('shares.destroy', 0))
            ->assertStatus(404);
    }

    /**
     * Test unauthenticated response.
     */
    public function test_unauthenticated(): void
    {
        $this->withHeaders(['Accept' => 'application/json'])
            ->delete(route('shares.destroy', 0))
            ->assertStatus(401);
    }
}

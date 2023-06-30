<?php

namespace Tests\Feature\Share;

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

        $post = Post::factory()->create();
        $user = User::factory()->make();

        $payload = [
            'post_id' => $post->id
        ];

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('shares.store'), $payload)
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
            ->post(route('shares.store'))
            ->assertStatus(422);
    }

    /**
     * Test unauthenticated response.
     */
    public function test_unauthenticated(): void
    {
        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route('shares.store'))
            ->assertStatus(401);
    }
}

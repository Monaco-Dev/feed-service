<?php

namespace Tests\Feature\Post;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Http\Middleware\PersonalAccessTokenAuthorization;
use App\Models\Post;
use App\Models\User;

class RestoreTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'posts.restore';

    /**
     * Test not found response.
     */
    public function test_not_found(): void
    {
        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route, 1))
            ->assertNotFound();
    }

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $this->withoutMiddleware([PersonalAccessTokenAuthorization::class]);

        $user = User::factory()->hasPosts()->create();

        $post = $user->posts()->first();

        $post->delete();

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route, $post->id))
            ->assertOk();
    }

    /**
     * Test unauthorized response.
     */
    public function test_unauthorized(): void
    {
        $this->withoutMiddleware([PersonalAccessTokenAuthorization::class]);

        $user = User::factory()->create();
        $post = Post::factory()->create();

        $post->delete();

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route, $post->id))
            ->assertForbidden();
    }
}

<?php

namespace Tests\Feature\Post;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Http\Middleware\PersonalAccessTokenAuthorization;
use App\Http\Middleware\Post as MiddlewarePost;
use App\Models\Post;
use App\Models\User;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'posts.show';

    /**
     * Test not found response.
     */
    public function test_not_found(): void
    {
        $this->withoutMiddleware([PersonalAccessTokenAuthorization::class]);

        $user = User::factory()->hasLicense()->create();

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route($this->route, 1))
            ->assertNotFound();
    }

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $this->withoutMiddleware([PersonalAccessTokenAuthorization::class, MiddlewarePost::class]);

        $user = User::factory()->hasLicense()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route($this->route, $post->uuid))
            ->assertOk();
    }
}

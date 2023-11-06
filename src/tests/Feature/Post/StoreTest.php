<?php

namespace Tests\Feature\Post;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Http\Middleware\PersonalAccessTokenAuthorization;
use App\Models\Post;
use App\Models\User;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'posts.store';

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $this->withoutMiddleware([PersonalAccessTokenAuthorization::class]);

        $user = User::factory()->create();
        $post = Post::factory()->make();

        $payload = [
            'content' => $post->content['body'],
            'type' => $post->content['type']
        ];

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route), $payload)
            ->assertCreated();
    }
}

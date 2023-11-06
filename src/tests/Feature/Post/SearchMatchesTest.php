<?php

namespace Tests\Feature\Post;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Http\Middleware\PersonalAccessTokenAuthorization;
use App\Models\User;

class SearchMatchesTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'posts.search.matches';

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $this->withoutMiddleware([PersonalAccessTokenAuthorization::class]);

        $user = User::factory()->hasBrokerLicense()->hasPosts()->create();

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route, $user->posts()->first()))
            ->assertOk();
    }
}

<?php

namespace Tests\Feature\Post;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Http\Middleware\PersonalAccessTokenAuthorization;
use App\Models\User;

class UnpinTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'posts.unpin';

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

        $user = User::factory()->create();
        $dummy = User::factory()->hasLicense()->hasPosts()->create();

        $user->pins()->attach($dummy->posts()->first());

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route, $dummy->posts()->first()))
            ->assertOk();
    }

    /**
     * Test unauthorized response.
     */
    public function test_unauthorized(): void
    {
        $this->withoutMiddleware([PersonalAccessTokenAuthorization::class]);

        $user = User::factory()->create();
        $dummy = User::factory()->unverified()->hasPosts()->create();

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route, $dummy->posts()->first()))
            ->assertForbidden();
    }
}

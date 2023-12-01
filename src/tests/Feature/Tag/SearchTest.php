<?php

namespace Tests\Feature\Tag;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Http\Middleware\PersonalAccessTokenAuthorization;
use App\Models\User;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'tags.search';

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $this->withoutMiddleware([PersonalAccessTokenAuthorization::class]);

        $user = User::factory()->hasBrokerLicense()->hasPosts()->create();

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route))
            ->assertOk();
    }
}

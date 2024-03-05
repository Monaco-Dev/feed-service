<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Post;
use App\Models\User;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = fake()->words();

        $users = User::cursor();

        $users->each(function ($user, $key) use ($users, $tags) {
            // Create posts
            Post::factory()
                ->count(3)
                ->create(['user_id' => $user->id])
                ->each(function ($post) use ($tags) {
                    $post->syncTags($tags);
                });

            if ($key + 1 <= count($users) - 1) {
                $network = new User($users->toArray()[$key + 1]);

                if (count($user->posts) > 1 && count($network->posts) > 1) {
                    // Create shares
                    $user->shares()->attach($network->posts[0]);
                    $network->shares()->attach($user->posts[0]);

                    // Create pins
                    $user->pins()->attach($network->posts[1]);
                    $network->pins()->attach($user->posts[1]);
                }
            }
        });
    }
}

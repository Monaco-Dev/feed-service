<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Post;

class CleanDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // $int = 1;

            // for ($int; $int <= 45; $int++) {
            //     $posts = Post::whereUserId($int)->get();

            //     foreach ($posts as $post) {
            //         $post->shares()->forceDelete();
            //         $post->pins()->forceDelete();

            //         Post::where('content->post_id', $post->id)->forceDelete();

            //         $post->syncTags([]);

            //         $post->forceDelete();
            //     }
            // }

            // $posts = Post::whereIn('user_id', [72])->get();

            // foreach ($posts as $post) {
            //     $post->shares()->forceDelete();
            //     $post->pins()->forceDelete();

            //     Post::where('content->post_id', $post->id)->forceDelete();

            //     $post->syncTags([]);

            //     $post->forceDelete();
            // }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

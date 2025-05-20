<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Comment;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $comments = Comment::factory(200)->create();

        $comments->each(function ($comment) use ($comments) {
            if (rand(0, 1)) {
                $parent = $comments->filter(function ($comment) { return $comment->parent_id === null; })->random();

                if ($parent->id !== $comment->id) {
                    $comment->parent_id = $parent->id;
                    $comment->save();
                }
            }
        });

        $this->call(FixCommentChildCountCollum::class);
    }
}

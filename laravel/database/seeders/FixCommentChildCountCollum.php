<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FixCommentChildCountCollum extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Comment::query()->chunk(50, function($comments) {
            /** @var Comment $comment */
            foreach ($comments as $comment) {
                $childCount = $comment->childComments()->count();
                if ($childCount) {
                    $comment->child_comments_count = $comment->childComments()->count();
                    $comment->save();
                }
           }
        });
    }
}

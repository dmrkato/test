<?php

namespace App\Jobs\Comment;

use App\Services\CommentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ForceDeleteCommentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     */
    public function __construct(
        protected int $commentId
    ) {
        $this->onQueue('force-delete-comment');
    }

    /**
     * Execute the job.
     */
    public function handle(
        CommentService $commentService,
    ): void
    {
        $commentService->delete($this->commentId, true);
    }
}

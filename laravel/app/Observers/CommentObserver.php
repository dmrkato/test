<?php

namespace App\Observers;

use App\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;

class CommentObserver
{
    public function __construct(
        private readonly CommentRepositoryInterface $commentRepository
    ) {
    }

    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        if ($comment->parent_id) {
            $parentComment = $this->commentRepository->getById($comment->parent_id);
            $parentComment->increment('child_comments_count');
        }
    }

    /**
     * Handle the Comment "updated" event.
     */
    public function updated(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "deleted" event.
     */
    public function deleted(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "deleting" event.
     */
    public function deleting(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "restored" event.
     */
    public function restored(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "force deleted" event. After SQL DELETE
     */
    public function forceDeleted(Comment $comment): void
    {

    }

    /**
     * Handle the Comment "force deleting" event. Before SQL DELETE
     */
    public function forceDeleting(Comment $comment): void
    {
        $comment->attachments()->each(function ($attachment) {
            $attachment->delete();
        });
    }
}

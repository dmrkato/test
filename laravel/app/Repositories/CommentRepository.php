<?php

namespace App\Repositories;

use App\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;
use App\Models\CommentAttachment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class CommentRepository implements CommentRepositoryInterface
{
    /**
     * @param int $id
     * @return Comment
     * @throws ModelNotFoundException<Comment>
     */
    public function getById(int $id): Comment
    {
        return Comment::withTrashed()->findOrFail($id);
    }

    /**
     * @param array $data
     * @return Comment
     */
    public function create(array $data): Comment
    {
        $comment = new Comment();
        $comment->user_name = $data['user_name'];
        $comment->email = $data['email'];
        $comment->home_page = $data['home_page'] ?? null;
        $comment->text = $data['text'];
        $comment->parent_id = $data['parent_id'] ?? null;

        $comment->save();

        return $comment;
    }

    /**
     * @param Comment|int $comment
     * @return Comment
     * @throws ModelNotFoundException<Comment>
     */
    public function restore(Comment|int$comment): Comment
    {
        $this->checkCommentArg($comment);

        $comment->restore();

        return $comment;
    }

    /**
     * @param Comment|int $comment
     * @param bool $force
     * @return void
     * @throws ModelNotFoundException<Comment>
     */
    public function delete(Comment|int $comment, bool $force = false): void
    {
        $this->checkCommentArg($comment);

        DB::transaction(function () use ($comment, $force) {
            if ($force) {
                $comment->forceDelete();
            } else {
                $comment->delete();
            }
        });

    }

    /**
     * @param Comment|int $comment
     * @param array $commentAttachments
     * @return void
     * @throws ModelNotFoundException<Comment>
     */
    public function attach(Comment|int $comment, array $commentAttachments): void
    {
        $this->checkCommentArg($comment);

        foreach ($commentAttachments as $key => $commentAttachment) {
            if (!($commentAttachment instanceof CommentAttachment)) {
                unset($commentAttachments[$key]);
            }
        }

        $comment->attachments()->saveMany($commentAttachments);
    }

    /**
     * @param Comment|int $comment
     * @return void
     * @throws ModelNotFoundException<Comment>
     */
    private function checkCommentArg(Comment|int &$comment): void
    {
        if (is_int($comment)) {
            $comment = $this->getById($comment);
        }
    }
}

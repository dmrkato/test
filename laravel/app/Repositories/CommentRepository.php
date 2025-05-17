<?php

namespace App\Repositories;

use App\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;
use App\Models\CommentAttachment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CommentRepository implements CommentRepositoryInterface
{

    /**
     * @param int $page
     * @param int $perPage
     * @param string $orderBy
     * @param string $direction
     * @param int|null $parentId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function list(
        int $page = 1,
        int $perPage = 25,
        string $orderBy = 'created_at',
        string $direction = 'desc',
        ?int $parentId = null
    ): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = Comment::query();
        $query->with([
            'attachments',
            'childComments' => function ($query) {
                $query->latest()->limit(5);
            },
        ]);
        $query->orderBy($orderBy, $direction);
        if ($parentId !== null) {
            $query->where('parent_id', $parentId);
        }

        return $query->paginate($perPage, ['*'], 'page', $page)->appends(request()->query());
    }


    /**
     * @param int $id
     * @return Comment
     * @throws ModelNotFoundException<Comment>
     */
    public function getById(int $id): Comment
    {
        return Comment::withTrashed()->with([
            'attachments',
            'childComments' => function ($query) {
                $query->latest()->limit(5);
            },
        ])->findOrFail($id);
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

    public function update(Comment|int $comment, array $data): Comment
    {
        $comment = $this->updateCommentArg($comment);

        $comment->fill($data);

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
        $this->updateCommentArg($comment);

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
        $comment = $this->updateCommentArg($comment);

        if ($force) {
            $comment->forceDelete();
        } else {
            $comment->delete();
        }

    }

    /**
     * @param Comment|int $comment
     * @param array $commentAttachments
     * @return void
     * @throws ModelNotFoundException<Comment>
     */
    public function attach(Comment|int $comment, array $commentAttachments): void
    {
        $comment = $this->updateCommentArg($comment);

        foreach ($commentAttachments as $key => $commentAttachment) {
            if (!($commentAttachment instanceof CommentAttachment)) {
                unset($commentAttachments[$key]);
            }
        }

        $comment->attachments()->saveMany($commentAttachments);
        $comment->load('attachments');
    }

    /**
     * @param Comment|int $comment
     * @return Comment
     * @throws ModelNotFoundException<Comment>
     */
    private function updateCommentArg(Comment|int &$comment): Comment
    {
        if (is_int($comment)) {
            $comment = $this->getById($comment);
        }

        return $comment;
    }
}

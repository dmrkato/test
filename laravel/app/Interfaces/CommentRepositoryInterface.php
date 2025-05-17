<?php

namespace App\Interfaces;

use App\Models\Comment;
use App\Models\CommentAttachment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CommentRepositoryInterface
{

    public function list(
        int $page,
        int $perPage,
        string $orderBy,
        string $direction,
        ?int $parentId = null,
    ): LengthAwarePaginator;
    /**
     * @param int $id
     * @return Comment
     */
    public function getById(int $id): Comment;

    /**
     * @param array $data
     * @return Comment
     */
    public function create(array $data): Comment;

    /**
     * @param Comment|int $comment
     * @param array $data
     * @return Comment
     */
    public function update(Comment|int $comment, array $data): Comment;

    public function restore(Comment|int $comment): Comment;

    /**
     * @param int|Comment $comment
     * @param bool $force
     * @return void
     */
    public function delete(Comment|int $comment, bool $force = false): void;

    /**
     * @param int|Comment $comment
     * @param CommentAttachment[] $commentAttachments
     * @return void
     */
    public function attach(Comment|int $comment, array $commentAttachments): void;
}

<?php

namespace App\Interfaces;

use App\DTO\CommentDTO;
use App\Models\Comment;
use App\Models\CommentAttachment;

interface CommentRepositoryInterface
{
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

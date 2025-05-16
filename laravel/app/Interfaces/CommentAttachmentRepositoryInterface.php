<?php

namespace App\Interfaces;

use App\Models\CommentAttachment;

interface CommentAttachmentRepositoryInterface
{
    /**
     * @param int $id
     * @return CommentAttachment
     */
    public function getById(int $id): CommentAttachment;

    /**
     * @param array $data
     * @return CommentAttachment
     */
    public function create(array $data): CommentAttachment;

    /**
     * @param CommentAttachment|int $commentAttachment
     * @return void
     */
    public function delete(CommentAttachment|int $commentAttachment): void;

}

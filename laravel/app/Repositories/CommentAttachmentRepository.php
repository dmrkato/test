<?php

namespace App\Repositories;

use App\Interfaces\CommentAttachmentRepositoryInterface;
use App\Models\CommentAttachment;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentAttachmentRepository implements CommentAttachmentRepositoryInterface
{
    /**
     * @param int $id
     * @return CommentAttachment
     * @throws ModelNotFoundException<CommentAttachment>
     */
    public function getById(int $id): CommentAttachment
    {
        return CommentAttachment::query()->findOrFail($id);
    }

    /**
     * @param array $data
     * @return CommentAttachment
     */
    public function create(array $data): CommentAttachment
    {
        $commentAttachment = new CommentAttachment();

        $commentAttachment->path = $data['path'];
        $commentAttachment->mime_type = $data['mime_type'];
        $commentAttachment->comment_id = $data['comment_id'] ?? null;

        $commentAttachment->save();

        return $commentAttachment;
    }

    public function delete(CommentAttachment|int $commentAttachment): void
    {
        $this->checkCommentAttachmentArg($commentAttachment);
        $commentAttachment->delete();
    }

    /**
     * @param CommentAttachment|int $commentAttachment
     * @return void
     * @throws ModelNotFoundException<CommentAttachment>
     *
     */
    private function checkCommentAttachmentArg(CommentAttachment|int &$commentAttachment): void
    {
        if (is_int($commentAttachment)) {
            $commentAttachment = $this->getById($commentAttachment);
        }
    }
}

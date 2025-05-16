<?php

namespace App\Services;

use App\DTO\CommentAttachmentDTO;
use App\DTO\CommentDTO;
use App\Helper\FileHelper;
use App\Helper\HtmlPurifierHelper;
use App\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;
use App\Models\CommentAttachment;
use Illuminate\Support\Facades\DB;

class CommentService
{
    public function __construct(
        protected readonly HtmlPurifierHelper       $htmlPurifierService,
        protected readonly CommentAttachmentService $commentAttachmentService,
        protected readonly CommentRepositoryInterface $commentRepository,
    ) {
    }

    /**
     * @param CommentDTO $commentDTO
     * @param CommentAttachmentDTO[] $commentAttachmentDTOs
     * @return Comment
     * @throws \Throwable
     */
    public function create(CommentDTO $commentDTO, array $commentAttachmentDTOs = []): Comment
    {
        $comment = null;
        try {
        DB::transaction(function () use (&$comment, $commentDTO, $commentAttachmentDTOs) {
            $comment = $this->commentRepository->create($commentDTO->toArray());
            $commentAttachments = [];
            foreach ($commentAttachmentDTOs as $commentAttachmentDTO) {
                $commentAttachments[] = new CommentAttachment($commentAttachmentDTO->toArray());
            }

            $this->commentRepository->attach($comment, $commentAttachments);
        });
        } catch (\Throwable $exception) {
            $filePaths = [];
            foreach ($commentAttachmentDTOs as $commentAttachmentDTO) {
                $filePaths[] = $commentAttachmentDTO->getPath();
            }
            FileHelper::deleteFiles($filePaths);
            throw $exception;
        }

        return $comment;
    }

    public function delete(Comment|int $comment, $force = false): void
    {
        $this->commentRepository->delete($comment, $force);
    }

    public function restore(Comment|int $comment): Comment
    {
        return $this->commentRepository->restore($comment);
    }
}

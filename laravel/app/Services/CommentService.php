<?php

namespace App\Services;

use App\DTO\CommentAttachmentDTO;
use App\DTO\CommentDTO;
use App\DTO\CommentListFilterDTO;
use App\Helper\FileHelper;
use App\Helper\HtmlPurifierHelper;
use App\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;
use App\Models\CommentAttachment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
     * @param int $id
     * @return Comment
     */
    public function getById(int $id): Comment
    {
        return $this->commentRepository->getById($id);
    }


    public function getList(CommentListFilterDTO $filters): LengthAwarePaginator
    {
        return $this->commentRepository->list(
            $filters->page,
            $filters->perPage,
            $filters->sortBy,
            $filters->sortDirection,
            $filters->parentId
        );
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

    /**
     * @param Comment|int $comment
     * @param $force
     * @return void
     */
    public function delete(Comment|int $comment, $force = false): void
    {
        $this->commentRepository->delete($comment, $force);
    }

    /**
     * @param Comment|int $comment
     * @return Comment
     */
    public function restore(Comment|int $comment): Comment
    {
        return $this->commentRepository->restore($comment);
    }
}

<?php

namespace App\Jobs\Comment;

use App\DTO\CommentAttachmentDTO;
use App\DTO\CommentDTO;
use App\Services\CommentAttachmentService;
use App\Services\CommentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateCommentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param CommentDTO $commentDTO
     * @param CommentAttachmentDTO[] $commentAttachmentDTOs
     */
    public function __construct(
        protected readonly CommentDTO $commentDTO,
        protected readonly array      $commentAttachmentDTOs,
    ) {
        $this->onQueue('create-comment');
    }

    public function handle(CommentService $commentService, CommentAttachmentService $commentAttachmentService)
    {
        $comment = $commentService->create($this->commentDTO, $this->commentAttachmentDTOs);
    }
}

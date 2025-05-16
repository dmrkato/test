<?php

namespace App\Http\Controllers\API\V1;

use App\DTO\CommentDTO;
use App\Helper\HtmlPurifierHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\CreateCommentRequest;
use App\Jobs\Comment\CreateCommentJob;
use App\Jobs\Comment\ForceDeleteCommentJob;
use App\Jobs\Comment\RestoreCommentJob;
use App\Jobs\Comment\SoftDeleteCommentJob;
use App\Services\CommentAttachmentService;
use App\Services\CommentService;

class CommentController extends Controller
{

    public function __construct(
        protected readonly CommentService $commentService,
        protected readonly CommentAttachmentService $attachmentService,
        protected readonly HtmlPurifierHelper $htmlPurifierService,
    ) {

    }
    public function create(CreateCommentRequest $request) {
        $commentData = $request->input();
        $commentDTO = new CommentDTO(
            user_name: $commentData['user_name'],
            email: $commentData['email'],
            text: $this->htmlPurifierService->purify($commentData['text']),
            home_page: $commentData['home_page'] ?? null,
            parent_id: $commentData['parent_id'] ?? null,
        );
        $attachments = $request->file('attachments') ?? [];

        $commentAttachments = $this->attachmentService->saveFiles($attachments);

        CreateCommentJob::dispatch($commentDTO, $commentAttachments);

        return response()->json(['message' => 'Comment creation queued'], 200);
    }

    public function restore(int $commentId)
    {
        RestoreCommentJob::dispatch($commentId);

        return response()->json(['message' => 'Comment restoration queued'], 200);
    }

    public function softDelete(int $id)
    {
        SoftDeleteCommentJob::dispatch($id);

        return response()->json(['message' => 'Comment soft delete queued'], 200);
    }

    public function forceDelete(int $id)
    {
        ForceDeleteCommentJob::dispatch($id);

        return response()->json(['message' => 'Comment force delete queued'], 200);
    }
}

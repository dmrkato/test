<?php

namespace App\Http\Controllers\API\V1;

use App\DTO\CommentDTO;
use App\DTO\CommentListFilterDTO;
use App\Helper\HtmlPurifierHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\CreateCommentRequest;
use App\Http\Requests\API\V1\GetCommentListRequest;
use App\Http\Resources\CommentPaginationResource;
use App\Http\Resources\CommentResource;
use App\Jobs\Comment\CreateCommentJob;
use App\Jobs\Comment\ForceDeleteCommentJob;
use App\Jobs\Comment\RestoreCommentJob;
use App\Jobs\Comment\SoftDeleteCommentJob;
use App\Services\CommentAttachmentService;
use App\Services\CommentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentController extends Controller
{

    public function __construct(
        protected readonly CommentService $commentService,
        protected readonly CommentAttachmentService $attachmentService,
        protected readonly HtmlPurifierHelper $htmlPurifierService,
    ) {

    }

    public function showById(int $id): JsonResource|JsonResponse
    {
        try {
            $comment = $this->commentService->getById($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        return CommentResource::make($comment);
    }

    public function getList(GetCommentListRequest $request): JsonResource|JsonResponse
    {
        $filters = new CommentListFilterDTO(
            page: (int) $request->input('page', 1),
            perPage: (int) $request->input('perPage', 25),
            sortBy: (string) $request->input('sortBy', 'created_at'),
            sortDirection: (string) $request->input('sortDirection', 'desc'),
            parentId: $request->get('parent_id', null),
        );

        $commentPagination = $this->commentService->getList($filters);

        return CommentPaginationResource::make($commentPagination);

    }

    /**
     * @param CreateCommentRequest $request
     * @return JsonResponse
     */
    public function create(CreateCommentRequest $request): JsonResponse {
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

    public function restore(int $commentId): JsonResponse
    {
        RestoreCommentJob::dispatch($commentId);

        return response()->json(['message' => 'Comment restoration queued'], 200);
    }

    public function softDelete(int $id): JsonResponse
    {
        SoftDeleteCommentJob::dispatch($id);

        return response()->json(['message' => 'Comment soft delete queued'], 200);
    }

    public function forceDelete(int $id): JsonResponse
    {
        ForceDeleteCommentJob::dispatch($id);

        return response()->json(['message' => 'Comment force delete queued'], 200);
    }
}

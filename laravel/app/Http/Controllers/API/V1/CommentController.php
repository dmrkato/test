<?php

namespace App\Http\Controllers\API\V1;

use App\DTO\CommentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\CreateCommentRequest;
use Illuminate\Http\Request;
use App\Services\CommentService;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{

    public function __construct(
        protected readonly CommentService $commentService
    ) {

    }
    public function create(CreateCommentRequest $request) {
        $data = $request->all();

        $commentDTO = new CommentDTO(...$data);

        $comment = $this->commentService->create($commentDTO);

        return new CommentResource($comment);
    }
}

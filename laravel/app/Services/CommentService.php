<?php

namespace App\Services;

use App\DTO\CommentDTO;
use App\Models\Comment;
use App\Models\CommentAttachment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CommentService
{
    public function __construct(
        protected readonly ImageManager $imageManager,
        protected readonly HtmlPurifierService $htmlPurifierService,
    ) {
    }

    /**
     * @param CommentDTO $commentDTO
     * @return Comment
     * @throws Throwable
     */
    public function create(CommentDTO $commentDTO): Comment
    {
        $filePaths = [];//use to delete files if exception happen
        $comment = null;
        try {
            DB::transaction(function () use ($commentDTO, &$comment, &$filePaths) {
                $comment = $this->createComment($commentDTO);
                $commentAttachments = $this->createCommentAttachment($commentDTO, $comment->id);
                foreach ($commentAttachments as $commentAttachment) {
                    $filePaths[] = $commentAttachment->path;
                }

                $comment->attachments()->saveMany($commentAttachments);
            });
        } catch (Throwable $e) {
            Storage::disk('public')->delete($filePaths);
            throw $e;
        }

        return $comment;
    }

    protected function createComment(CommentDTO $commentDTO): Comment
    {
        $comment = new Comment();
        $comment->user_name = $commentDTO->getUserName();
        $comment->email = $commentDTO->getEmail();
        $comment->home_page = $commentDTO->getHomePage();
        $comment->text = $this->htmlPurifierService->purify($commentDTO->getText());
        $comment->parent_id = $commentDTO->getParentId();

        $comment->save();

        return $comment;
    }

    protected function createCommentAttachment(CommentDTO $commentDTO, int $commentId): array
    {
        $attachments = [];
        $storage = Storage::disk('public');
        foreach ($commentDTO->getAttachments() as $file) {
            $attachment = new CommentAttachment();
            $mimeType = $file->getMimeType();
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = implode('/', [CommentAttachment::storageDir(), $filename[0], $filename[1], $filename]);
            if (str_starts_with($mimeType, 'image/')) {
                // Create Intervention Image
                $image = $this->imageManager->read($file->getPathname());

                // Resize image to 320x240 (if image to big and aspect ratio)
                $image->scale(320, 240);

                $storage->put($path, (string) $image->encode());
            } else {
                $storage->put($path, $file->getContent());
            }

            $attachment->path = $path;
            $attachment->mime_type = $mimeType;
            $attachment->comment_id = $commentId;

            $attachments[] = $attachment;
        }

        return $attachments;
    }
}

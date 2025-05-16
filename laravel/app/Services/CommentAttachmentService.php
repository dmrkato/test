<?php

namespace App\Services;

use App\DTO\CommentAttachmentDTO;
use App\Helper\FileHelper;
use App\Interfaces\CommentAttachmentRepositoryInterface;
use App\Models\CommentAttachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CommentAttachmentService
{

    public  function __construct(
        protected readonly CommentAttachmentRepositoryInterface $commentAttachmentRepository,
    ) {
    }

    /**
     * @param UploadedFile[] $attachments
     * @return CommentAttachmentDTO[]
     */
    public function saveFiles(array $attachments, ?int $commentId = null): array
    {
        $result = [];

        $storage = Storage::disk('public');
        foreach ($attachments as $attachment) {
            if ($attachment instanceof UploadedFile) {
                /** @var UploadedFile $attachment */
                $attachmentDTOs = new CommentAttachmentDTO();
                $mimeType = $attachment->getMimeType();

                if (str_starts_with($mimeType, 'image/')) {
                    $path = FileHelper::saveImage(
                        $attachment,
                        CommentAttachment::storageDir(),
                        'public',
                        320,
                        240
                    );
                } else {
                    $path = FileHelper::generatePath($attachment, CommentAttachment::storageDir());
                    $storage->put($path, $attachment->getContent());
                }

                $attachmentDTOs->setPath($path);
                $attachmentDTOs->setMimeType($mimeType);
                $attachmentDTOs->setCommentId($commentId);

                $result[] = $attachmentDTOs;
            }
        }

        return $result;
    }
}

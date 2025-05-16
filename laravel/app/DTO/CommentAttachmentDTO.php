<?php

namespace App\DTO;

class CommentAttachmentDTO
{
    public function __construct(
        private ?int $id = null,
        private ?string $path = null,
        private ?string $mime_type = null,
        private ?string $comment_id = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'path' => $this->path,
            'mime_type' => $this->mime_type,
            'comment_id' => $this->comment_id,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mime_type;
    }

    public function setMimeType(?string $mime_type): self
    {
        $this->mime_type = $mime_type;
        return $this;
    }

    public function getCommentId(): ?string
    {
        return $this->comment_id;
    }

    public function setCommentId(?string $comment_id): self
    {
        $this->comment_id = $comment_id;
        return $this;
    }
}

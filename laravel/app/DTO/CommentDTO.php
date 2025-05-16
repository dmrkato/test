<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class CommentDTO
{
    /**
     * @param string $user_name
     * @param string $email
     * @param string $text
     * @param int|null $parent_id
     * @param UploadedFile[]|null $attachments
     */
    public function __construct(
        protected string $user_name,
        protected string $email,
        protected string $text,
        protected ?string $home_page = null,
        protected ?int $parent_id = null,
        protected ?array $attachments = null,
    ) {

    }

    public function toArray(): array
    {
        return [
            'user_name' => $this->user_name,
            'email' => $this->email,
            'text' => $this->text,
            'parent_id' => $this->parent_id,
            'attachments' => $this->attachments,
        ];
    }

    public function getUserName(): string
    {
        return $this->user_name;
    }

    public function setUserName(string $user_name): self
    {
        $this->user_name = $user_name;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getHomePage(): ?string
    {
        return $this->home_page;
    }

    public function setHomePage(?string $home_page): self
    {
        $this->home_page = $home_page;

        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    public function setParentId(?int $parent_id): self
    {
        $this->parent_id = $parent_id;

        return $this;
    }

    /**
     * @return UploadedFile[]|null
     */
    public function getAttachments(): ?array
    {
        return $this->attachments;
    }

    /**
     * @param UploadedFile[]|null $attachments
     * @return $this
     */
    public function setAttachments(?array $attachments): self
    {
        $this->attachments = $attachments;

        return $this;
    }
}

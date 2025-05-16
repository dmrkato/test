<?php

namespace App\DTO;

use App\Models\CommentAttachment;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CommentDTO
{
    /**
     * @param string|null $user_name
     * @param string|null $email
     * @param string|null $text
     * @param string|null $home_page
     * @param int|null $parent_id
     */
    public function __construct(
        protected ?int $id = null,
        protected ?string $user_name = null,
        protected ?string $email = null,
        protected ?string $text = null,
        protected ?string $home_page = null,
        protected ?int $parent_id = null,
    ) {

    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_name' => $this->user_name,
            'email' => $this->email,
            'text' => $this->text,
            'home_page' => $this->home_page,
            'parent_id' => $this->parent_id,
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

    public function getUserName(): ?string
    {
        return $this->user_name;
    }

    public function setUserName(?string $user_name): self
    {
        $this->user_name = $user_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
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
}

<?php

namespace App\DTO;

class CommentListFilterDTO
{
    public function __construct(
        public readonly int $page = 1,
        public readonly int $perPage = 25,
        public readonly string $sortBy = 'created_at',
        public readonly string $sortDirection = 'desc',
        public readonly ?int $parentId = null,
    ) {
    }
}

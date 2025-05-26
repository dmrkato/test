<?php

namespace App\Repositories;

use App\Interfaces\CommentRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use App\Models\Comment;

class CachedCommentRepository implements CommentRepositoryInterface
{

    private const int TTL = 30;
    public function __construct(
        private readonly CommentRepository $repository
    ) {
    }

    public function list(
        int $page = 1,
        int $perPage = 25,
        string $orderBy = 'created_at',
        string $direction = 'desc',
        ?int $parentId = null
    ): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $cacheKey = $this->getListCacheKey($page, $perPage, $orderBy, $direction, $parentId);
        $paginaton = Cache::remember($cacheKey, 60, function () use ($page, $perPage, $orderBy, $direction, $parentId) {
            return $this->repository->list($page, $perPage, $orderBy, $direction, $parentId);
        });

        return $paginaton;
    }


    /**
     * @param int $id
     * @return Comment
     */
    public function getById(int $id): Comment
    {
        return Cache::remember($this->getCacheKey($id), self::TTL , function () use ($id) {
            return $this->repository->getById($id);
        });
    }

    /**
     * @param array $data
     * @return Comment
     */
    public function create(array $data): Comment
    {
        $comment = $this->repository->create($data);

        Cache::set($this->getCacheKey($comment), $comment, self::TTL);

        return $comment;
    }

    public function update(Comment|int $comment, array $data): Comment
    {
        $comment = $this->updateCommentArg($comment);
        $cacheKey = $this->getCacheKey($comment);

        Cache::forget($cacheKey);

        $comment = $this->repository->update($comment, $data);

        Cache::Set($cacheKey, $comment, self::TTL);

        return $comment;
    }

    public function restore(Comment|int $comment): Comment
    {
        $comment = $this->updateCommentArg($comment);

        $cacheKey = $this->getCacheKey($comment);

        Cache::forget($cacheKey);

        $this->repository->restore($comment);

        Cache::set($cacheKey, $comment, self::TTL);

        return $comment;
    }

    public function delete(Comment|int $comment, bool $force = false): void
    {
        $comment = $this->updateCommentArg($comment);

        $cacheKey = $this->getCacheKey($comment);

        Cache::forget($cacheKey);

        $repository = $this->repository;

        $repository->delete($comment, $force);

        if (!$force) {
            Cache::set($cacheKey, $comment, self::TTL);
        }
    }

    public function attach(Comment|int $comment, array $commentAttachments): void
    {
        $comment = $this->updateCommentArg($comment);

        $cacheKey = $this->getCacheKey($comment);

        Cache::forget($cacheKey);

        $this->repository->attach($comment, $commentAttachments);

        Cache::Set($cacheKey, $comment, self::TTL);
    }

    private function getCacheKey(Comment|int $commentId): string
    {
        if ($commentId instanceof Comment) {
            $commentId = $commentId->id;
        }

        return 'comment.' . $commentId;
    }

    private function getListCacheKey(int $page, int $perPage, string $orderBy, string $direction, ?int $parentId = null): string
    {
        return 'comments.list.page.' . $page . '.perPage.' . $perPage
            . '.orderBy.' . $orderBy . '.direction.' . $direction . '.parentId.' . $parentId ;
    }

    private function updateCommentArg(Comment|int $comment): Comment
    {
        if (is_int($comment)) {
            $comment = $this->getById($comment);
        }

        return $comment;
    }
}

<?php

namespace App\Providers;

use App\Models\Comment;
use App\Observers\CommentObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Models\CommentAttachment;
use App\Observers\CommentAttachmentObserver;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
    ];

    public function boot(): void
    {
        Comment::observe(CommentObserver::class);
        CommentAttachment::observe(CommentAttachmentObserver::class);
    }
}

<?php

namespace App\Observers;

use App\Helper\FileHelper;
use App\Models\CommentAttachment;

class CommentAttachmentObserver
{
    /**
     * Handle the CommentAttachment "created" event.
     */
    public function created(CommentAttachment $commentAttachment): void
    {
        //
    }

    /**
     * Handle the CommentAttachment "updated" event.
     */
    public function updated(CommentAttachment $commentAttachment): void
    {
        //
    }

    /**
     * Handle the CommentAttachment "deleted" event.
     */
    public function deleted(CommentAttachment $commentAttachment): void
    {
        FileHelper::deleteFiles($commentAttachment->path);
    }
}

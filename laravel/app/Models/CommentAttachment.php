<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentAttachment extends Model
{
    protected $fillable = [
        'path', 'mime_type', 'comment_id',
    ];

    public static function storageDir(): string
    {
        return 'comments/attachments';
    }
}

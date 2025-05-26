<?php

namespace App\Models;

use App\Helper\HtmlPurifierHelper;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes,HasFactory;
    protected $fillable = [
        'user_name', 'email', 'text', 'home_page', 'parent_id', 'child_comments_count'
    ];

    public static function childCommentLimit(): int {
        return 5;
    }

    public function parentComment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id', 'id');
    }

    public function childComments(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(CommentAttachment::class, 'comment_id', 'id');
    }

    protected function text(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return app(HtmlPurifierHelper::class)->purify($value);
            }
        );
    }

}

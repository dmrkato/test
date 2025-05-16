<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Services\HtmlPurifierService;

class Comment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_name', 'email', 'text', 'home_page', 'parent_id'
    ];

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
                return app(HtmlPurifierService::class)->purify($value);
            }
        );
    }

}

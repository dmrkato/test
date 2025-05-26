<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $result = [
            'id' => $this->id,
            'user_name' => $this->user_name,
            'email' => $this->email,
            'home_url' => $this->home_url,
            'text' => $this->text,
            'attachments' => $this->attachments->toArray(),
            'child_comments' => [],
            'child_comments_count' => $this->child_comments_count,
            'parent_id' => $this->parent_id,
            'created_at' => $this->created_at,
        ];

        /** @var Comment $resource */
        $resource = $this->resource;
        if ($resource->relationLoaded('childComments')) {
            // if loaded 2+ level of child comments - cut them
            $this->childComments->each->setRelation('childComments', collect());
            $result['child_comments'] = CommentResource::collection($this->childComments);
        }

        return $result;
    }
}

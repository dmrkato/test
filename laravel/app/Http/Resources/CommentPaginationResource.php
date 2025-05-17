<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentPaginationResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'data' => CommentResource::collection($this->collection),
        ];
    }

//    public function with($request)
//    {
//        return [
//            'meta' => [
//                'current_page' => $this->currentPage(),
//                'last_page' => $this->lastPage(),
//                'per_page' => $this->perPage(),
//                'total' => $this->total(),
//            ],
//        ];
//    }
}

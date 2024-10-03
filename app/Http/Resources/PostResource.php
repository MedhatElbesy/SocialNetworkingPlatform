<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'       => $this->id,
            'content'  => $this->content,
            'image'    => $this->image ? url('storage/' . $this->image) : null,
            'user'     => new UserResource($this->user),
            'comments' => CommentResource::collection($this->comments),
        ];
    }
}

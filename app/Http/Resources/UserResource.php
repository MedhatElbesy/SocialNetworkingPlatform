<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'email'     => $this->email,
            'image'    => $this->image ? url('storage/' . $this->image) : null,
            // 'image'     => $this->image ? asset('storage/' . $this->image) : null,
            'bio'       => $this->bio,
            "token"     => $this->when(isset($this->token), $this->token),

        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @OA\Schema(
 *   schema="UserResource",
 *   type="object",
 *   description="User resource response",
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="name", type="string", example="John Doe"),
 *   @OA\Property(property="email", type="string", example="john@example.com"),
 *   @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJh..."),
 *   @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
 *   @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T12:00:00Z")
 * )
 */

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

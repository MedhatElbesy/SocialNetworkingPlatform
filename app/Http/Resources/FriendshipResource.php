<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



/**
 * @OA\Schema(
 *     schema="FriendshipResource",
 *     type="object",
 *     title="Friendship Resource",
 *     description="Represents the friendship resource",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1,
 *         description="Friendship ID"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         example=10,
 *         description="The ID of the user who sent the request"
 *     ),
 *     @OA\Property(
 *         property="friend_id",
 *         type="integer",
 *         example=15,
 *         description="The ID of the user who received the request"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         example="pending",
 *         description="The status of the friendship request"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-10-05T12:34:56Z",
 *         description="Timestamp when the friendship request was created"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-10-05T12:45:56Z",
 *         description="Timestamp when the friendship request was last updated"
 *     )
 * )
 */
class FriendshipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'     => $this->id,
            'user'   => new UserResource($this->user),
            'friend' => new UserResource($this->friend),
            'status' => $this->status,
        ];
    }
}

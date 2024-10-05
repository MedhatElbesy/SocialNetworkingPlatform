<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *   schema="Post",
 *   type="object",
 *   description="Post model",
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="user_id", type="integer", example=1),
 *   @OA\Property(property="content", type="string", example="This is a post content"),
 *   @OA\Property(property="image", type="string", example="http://example.com/images/post.jpg"),
 *   @OA\Property(property="created_at", type="string", format="date-time", example="2023-08-30T12:34:56Z"),
 *   @OA\Property(property="updated_at", type="string", format="date-time", example="2023-08-30T12:34:56Z"),
 * )
 */

class Post extends Model
{
    use HasFactory;

        protected $fillable = [
        'user_id',
        'content',
        'image',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}

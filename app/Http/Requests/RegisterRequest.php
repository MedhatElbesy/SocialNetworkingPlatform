<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;



/**
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     type="object",
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="johndoe@example.com"),
 *     @OA\Property(property="password", type="string", example="password123"),
 *     @OA\Property(property="password_confirmation", type="string", example="password123"),
 *     @OA\Property(property="image", type="string", format="binary")
 * )
 */

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'    => 'required|email|unique:users',
            'name'     => 'required|regex:/^\S*$/u',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ]
        ];
    }
}


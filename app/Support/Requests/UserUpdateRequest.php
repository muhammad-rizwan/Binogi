<?php

namespace App\Support\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *   schema="UserUpdateRequest",
 *   description="User Update Request Body",
 *   @OA\Property(
 *      property="name",
 *      type="string",
 *      description="User name",
 *      example="Jane Doe",
 *      minLength=1,
 *      maxLength=191,
 *   ),
 *   @OA\Property(
 *      property="nickname",
 *      type="string",
 *      description="User's nickname",
 *      example="JD, JDoe",
 *      minLength=1,
 *      maxLength=30,
 *   ),
 *   @OA\Property(
 *      property="email",
 *      type="string",
 *      description="User email",
 *      example="JaneDoe@email.com",
 *      minLength=1,
 *      maxLength=191,
 *   ),
 *   @OA\Property(
 *      property="password",
 *      type="string",
 *      description="User Password",
 *      example="correct horse battery staple",
 *      minLength=1,
 *      maxLength=191,
 *   ),
 * )
 *
 * Get the validation rules that apply to the request.
 *
 * @return array
 */
class UserUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'string|max:191|min:1',
            'nickname' => [
                'required',
                'string',
                'min:1',
                'max:30',
                Rule::unique('users')->ignore(request()->route('user')->id),
            ],
            'password' => 'string|min:8|max:191',
            'email'    => [
                'email',
                Rule::unique('users')->ignore(request()->route('user')->id),
            ],
        ];
    }
}

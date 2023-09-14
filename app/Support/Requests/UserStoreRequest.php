<?php

namespace App\Support\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *   schema="UserStoreRequest",
 *   description="Create new user",
 *   required={"name", "nickname", "email", "password"},
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
 *
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
 */
class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => 'required|string|max:191|min:1',
            'nickname' => 'required|string|max:30|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8|max:191',
        ];
    }
}

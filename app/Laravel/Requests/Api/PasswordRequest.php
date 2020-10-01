<?php

namespace App\Laravel\Requests\Api;

use App\Laravel\Requests\ApiRequestManager;
// use JWTAuth;

class PasswordRequest extends ApiRequestManager
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $guard = $this->segment(2);

        $user = $this->user($guard);

        $rules = [
            'password' => "required|confirmed",
            'current_password' => "required|current_password:{$user->id},{$guard}",
            'password_confirmation' => "required"
        ];

        return $rules;
    }

    public function messages() {
        return [
            'required' => "Field is required.",
            'password.password_format' => "New password must be 2-25 characters long only and without space.",
            'current_password.current_password' => "Please put your current password.",
        ];
    }
}

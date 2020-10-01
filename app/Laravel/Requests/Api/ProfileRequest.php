<?php

namespace App\Laravel\Requests\Api;

use App\Laravel\Requests\ApiRequestManager;
// use JWTAuth;

class ProfileRequest extends ApiRequestManager
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
            'fname' => "required",
            'lname' => "required",
            'contact_number' => "nullable|phone:PH",
            // 'birthdate' => "required|date",
            // 'residence_address' => "required",
            // 'civil_status' => "required",
            'email' =>  "required|unique_email:{$user->id},officer",
            // 'username' =>  "required|unique_username:{$user->id},officer",
            // 'status' => "required"
        ];

        return $rules;
    }

    public function messages() {
        return [
            'required' => "Field is required.",
            'password.password_format' => "New password must be 2-25 characters long only and without space.",
            'current_password.old_password' => "Please put your current password.",
        ];
    }
}

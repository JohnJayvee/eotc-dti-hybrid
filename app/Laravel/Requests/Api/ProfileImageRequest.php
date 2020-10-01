<?php

namespace App\Laravel\Requests\Api;

use App\Laravel\Requests\ApiRequestManager;
// use JWTAuth;

class ProfileImageRequest extends ApiRequestManager
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){

        $rules = [
            'file' => "required|image",
        ];

        return $rules;
    }

    public function messages(){
        return [
            'required'  => "Image is required.",
            'image' => "Invalid image type.",
        ];
    }
}

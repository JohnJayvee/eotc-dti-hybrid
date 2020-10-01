<?php

namespace App\Laravel\Requests\Api;

use App\Laravel\Requests\ApiRequestManager;
// use JWTAuth;

class TravelHistoryRequest extends ApiRequestManager
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'citizen_code' => "nullable",
            'fname'         => "required_without:citizen_code",
            'lname'         => "required_without:citizen_code",
            'contact_number'         => "required_without:citizen_code",
            'brgy'         => "required_without:citizen_code",
            'residence_address'         => "required_without:citizen_code",
            'geo_lat'         => "required",
            'geo_long'         => "required",
            'reason_food_medicine'         => "required",
            'reason_hospital'         => "required",
            'reason_bank'         => "required",
            'reason_other'         => "required",
            'reason_other_description'         => "required_unless:reason_other,no",
            'type'         => "required",
            'status'         => "required",
        ];

        return $rules;
    }

    public function messages() {
        return [
            'required_without' => "Field is required.",
            'required_unless' => "Field is required.",
            'required' => "Field is required.",
        ];
    }
}

<?php namespace App\Laravel\Requests\Api;

use Session,Auth;
use App\Laravel\Requests\ApiRequestManager;

class CitizenIdentificationApplicationRequest extends ApiRequestManager{

	public function rules(){
		$rules = [
			'fname'         => "required",
            'lname'         => "required",
            'contact_number'  => "required",
            'birthdate'         => "required",
            'email'         => "nullable|email",
            'civil_status'         => "required",
            'residence_brgy'         => "required|valid_brgy",
            'residence_street'         => "required",
            'permanent_brgy'         => "required",
            'permanent_street'         => "required",
            'permanent_city'         => "required",
            'permanent_province'         => "required",
            'gross_income'         => "required",
            'file'				=> "required|image"
		];

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			'email' => "Invalid email address format.",
			'valid_brgy' => "Barangay not registered."
		];
	}
}
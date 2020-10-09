<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class SetupPasswordRequest extends RequestManager{

	public function rules(){

		$rules = [
			'password'	=> "required|password_format|confirmed",
			'reference_number' => "required",
		];

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			'confirmed' => "Password mismatch.",
			'password_format' => "Password must be 6-20 alphanumeric and some allowed special characters only.",
		];
	}
}
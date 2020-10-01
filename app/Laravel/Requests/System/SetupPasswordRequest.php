<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class SetupPasswordRequest extends RequestManager{

	public function rules(){

		$rules = [
			'password' => "required|confirmed",
			'reference_number' => "required",
		];

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			'confirmed' => "Password mismatch.",
		];
	}
}
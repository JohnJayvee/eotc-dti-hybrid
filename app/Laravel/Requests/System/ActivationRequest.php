<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class ActivationRequest extends RequestManager{

	public function rules(){

		$rules = [
			'otp' => "required",
			'reference_id' => "required",
			
		];

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			
		];
	}
}
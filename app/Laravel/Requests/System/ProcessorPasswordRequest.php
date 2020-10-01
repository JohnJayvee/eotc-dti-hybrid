<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class ProcessorPasswordRequest extends RequestManager{

	public function rules(){

		$rules = [
			'current_password' => "required",
			'password' => "required|confirmed",
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
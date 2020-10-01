<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class ProfilePasswordRequest extends RequestManager{

	public function rules(){

		$rules = [
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
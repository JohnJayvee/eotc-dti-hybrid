<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class ProfileRequest extends RequestManager{

	public function rules(){

		$rules = [
			'personal_email' => "required|email",
			'contact_number' => "required|phone:PH",
			'residence_address' => "required"
		];

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			'email'		=> "Invalid email address format.",
			'phone' => "Invalid Phone number format.",
		];
	}
}
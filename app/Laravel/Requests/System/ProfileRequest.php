<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class ProfileRequest extends RequestManager{

	public function rules(){
		$id = $this->route('id')?:0;
		$rules = [
			'email' => "required|email",
			'contact_number' => "required|phone:PH",
			'fname' => "required",
			'mname' => "required",
			'lname' => "required",
			
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
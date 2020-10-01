<?php namespace App\Laravel\Requests\Api;

use Session,Auth;
use App\Laravel\Requests\ApiRequestManager;

class RegisterRequest extends ApiRequestManager{

	public function rules(){
		$rules = [ 
			'type' => 'required',
			'fname' => 'required',
			'lname' => 'required',
			'email' => 'required|email|unique_email:0,citizen',
			'contact_number' => 'required|phone:mobile,PH',
			'password' => "required|confirmed"
		];

		return $rules;
	}

	public function messages(){
		return [
			'confirmed' => "Password mismatch.",
			'unique_username' => "Username already in used.",
			'required'	=> "Field is required.",
			'email'		=> "Invalid email address format.",
			'phone' => "Invalid Phone number format.",
			'integer' => "Invalid data.",

		];
	}
}
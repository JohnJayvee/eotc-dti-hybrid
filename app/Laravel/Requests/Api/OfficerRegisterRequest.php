<?php namespace App\Laravel\Requests\Api;

use Session,Auth;
use App\Laravel\Requests\ApiRequestManager;

class OfficerRegisterRequest extends ApiRequestManager{

	public function rules(){
		$rules = [ 
			'reference_code' => 'required|valid_reference_code',
			'fname' => 'required',
			'lname' => 'required',
			'email' => 'required|email|unique_email:0,officer',
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
			'valid_reference_code' => "Reference Code no longer available.",
			'unique_email' => "Email address already used."

		];
	}
}
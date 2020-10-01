<?php namespace App\Laravel\Requests\Api;

use Session,Auth;
use App\Laravel\Requests\ApiRequestManager;

class EmployeeRequest extends ApiRequestManager{

	public function rules(){
		$rules = [
			'name'	=> "required",
			'email'	=> "required|email",
		];

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			'email' => "Invalid email  address format."
		];
	}
}
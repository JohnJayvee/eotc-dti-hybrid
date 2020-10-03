<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class DepartmentRequest extends RequestManager{

	public function rules(){

		$rules = [
			'name' => "required|unique:department,name"
		];

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
		];
	}
}
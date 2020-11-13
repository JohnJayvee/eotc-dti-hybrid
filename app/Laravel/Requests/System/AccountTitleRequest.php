<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class AccountTitleRequest extends RequestManager{

	public function rules(){

		$rules = [
			'name' => "required",
			'department_id' => "required"
		];

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			'name.unique'	=> "The Bureau/Office name is already exist.",
		];
	}
}
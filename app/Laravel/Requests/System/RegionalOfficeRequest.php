<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class RegionalOfficeRequest  extends RequestManager{

	public function rules(){

		$rules = [
			'name' => "required",
			
		];

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
		];
	}
}
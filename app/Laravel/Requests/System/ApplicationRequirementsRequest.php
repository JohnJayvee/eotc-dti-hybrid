<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class ApplicationRequirementsRequest extends RequestManager{

	public function rules(){

		$rules = [
			'name' => "required",
			'is_required' => "required"
		];

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
		];
	}
}
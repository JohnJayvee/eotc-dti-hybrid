<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class UploadRequest extends RequestManager{

	public function rules(){

		$rules = [
			'file' => "required",
			
		];

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			
		];
	}
}
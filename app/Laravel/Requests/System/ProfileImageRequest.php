<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class ProfileImageRequest extends RequestManager{

	public function rules(){

		$rules = [
			'file' => "required|image",
		];

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Image is required.",
			'image' => "Invalid image type.",
		];
	}
}
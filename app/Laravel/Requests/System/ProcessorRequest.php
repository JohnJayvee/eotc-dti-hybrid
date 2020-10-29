<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class ProcessorRequest extends RequestManager{

	public function rules(){

		$id = $this->route('id')?:0;
		$rules = [
			'fname' => "required",
			'lname' => "required",
			'type' => "required",
			'contact_number' => "required|max:10|phone:PH",
			'email'	=> "required|unique:user,email,{$id}",
			'username'	=> "required|unique:user,username,{$id}",
			// 'file' => 'required|mimes:jpeg,jpg,png,JPEG,PNG|max:204800',
		];

		if ($this->get('type') == "processor") {
			$rules['application_id'] = "required";
			$rules['department_id'] = "required";
		}
		if ($this->get('type') == "office_head") {
			$rules['department_id'] = "required";
		}
		
		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			'contact_number.phone' => "Please provide a valid PH mobile number.",
			'file.mimes' => 'The file must be a file of type: jpeg, jpg, png, JPEG, PNG.'
		];
	}
}
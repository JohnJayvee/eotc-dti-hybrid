<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class AdminRequest extends RequestManager{

	public function rules(){

		$id = $this->route('id')?:0;
		$rules = [
			'fname' => "required",
			'lname' => "required",
			'region' => "required",
			'town' => "required",
			'brgy' => "required",
			'street_name' => "required",
			'unit_number' => "required",
			'zipcode' => "required",
			'birthdate' => "required",
			'tin_no' => "required",
			'sss_no' => "required",
			'phic_no' => "required",
			'contact_number' => "required|max:10|phone:PH",
			'email'	=> "required|unique:user,email,{$id}",
			'password'	=> "required|confirmed",
			
		];
		
		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			'contact_number.phone' => "Please provide a valid PH mobile number.",
			
		];
	}
}
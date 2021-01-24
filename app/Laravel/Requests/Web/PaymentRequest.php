<?php namespace App\Laravel\Requests\Web;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class Paymentrequest extends RequestManager{

	public function rules(){

		$rules = [
			'code' => "required",
		];
		
		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
		];
	}
}
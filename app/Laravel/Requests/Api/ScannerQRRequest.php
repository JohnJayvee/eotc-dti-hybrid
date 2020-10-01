<?php namespace App\Laravel\Requests\Api;

use Session,Auth;
use App\Laravel\Requests\ApiRequestManager;

class ScannerQRRequest extends ApiRequestManager{

	public function rules(){
		$rules = [
			'id_no'	=> "required",
			'geo_lat'	=> "required",
			'geo_long'	=> "required",
			'location' => "required"

		];

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
		];
	}
}
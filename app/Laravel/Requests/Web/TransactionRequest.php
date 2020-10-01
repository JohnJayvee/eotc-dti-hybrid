<?php namespace App\Laravel\Requests\Web;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class TransactionRequest extends RequestManager{

	public function rules(){

		$id = $this->route('id')?:0;
		$file = $this->file('file') ? count($this->file('file')) : 0;
		$rules = [
			'full_name' => "required",
			'company_name' => "required",
			'application_id' => "required",
			'department_id' => "required",
			'processing_fee' => "required",
			'regional_id' => "required",
			'contact_number' => "required|max:10|phone:PH",
    		'file.*' => 'required|mimes:pdf,docx,doc|max:204800',
		];
		if ($this->get('is_check') != 1 ) {
			$rules['file'] = "required";
		}
		if ($this->get('file_count') != 0) {
			$rules['file_count'] = "required|with_count:file_count,application_id";
		}
		return $rules;
		
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			'contact_number.phone' => "Please provide a valid PH mobile number.",
			'file.required'	=> "No File Uploaded.",
			'file.*' => 'Only PDF File are allowed.',
			'file_count.with_count' => 'Please Submit minimum requirements.'

		];
	}
}
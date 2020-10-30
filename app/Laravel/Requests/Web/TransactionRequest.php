<?php namespace App\Laravel\Requests\Web;

use Session,Auth;
use App\Laravel\Requests\RequestManager;
use App\Laravel\Models\ApplicationRequirements;
class TransactionRequest extends RequestManager{

	public function rules(){

		$id = $this->route('id')?:0;
		$file = $this->file('file') ? count($this->file('file')) : 0;

		$rules = [
			'full_name' => "required",
			'company_name' => "required",
			'application_id' => "required",
			'email' => "required",
			'contact_number' => "required",
			'department_id' => "required",
			'processing_fee' => "required",
			'partial_amount' => "nullable|minimum_amount:application_id,partial_amount",
			// 'regional_id' => "required",
			'contact_number' => "required|max:10|phone:PH",
		];

		$required = ApplicationRequirements::whereIn('id',explode(",", $this->get('requirements_id')))->where('is_required',"yes")->get();

		foreach ($required as $key => $value) {
			$rules['file'.$value->id] = "required|mimes:pdf,docx,doc|max:5000";
		}


		return $rules;
		
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			'partial_amount.minimum_amount' => ":message",
			'contact_number.phone' => "Please provide a valid PH mobile number.",
			'file.required'	=> "No File Uploaded.",
			'mimes' => 'Only PDF File are allowed.',
			'max' => 'This file is greater than allowed file size.',
			'file_count.with_count' => 'Please Submit minimum requirements.'

		];

	}
}
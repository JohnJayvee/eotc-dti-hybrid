<?php namespace App\Laravel\Requests\Web;

use Session,Auth;
use App\Laravel\Requests\RequestManager;
use App\Laravel\Models\ApplicationRequirements;
class UploadRequest extends RequestManager{

	public function rules(){

		$id = $this->route('id')?:0;
		$file = $this->file('file') ? count($this->file('file')) : 0;

		
		$required = ApplicationRequirements::whereIn('id',$this->get('requirement_id'))->get();

		foreach ($required as $key => $value) {
			$rules['file'.$value->id] = "required|mimes:pdf,docx,doc|max:5000";
		}


		return $rules;
		
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
		];
	}
}
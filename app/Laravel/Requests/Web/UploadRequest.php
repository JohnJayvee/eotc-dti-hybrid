<?php namespace App\Laravel\Requests\Web;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class UploadRequest extends RequestManager{

	public function rules(){

		$id = $this->route('id')?:0;
		$file = $this->file('file') ? count($this->file('file')) : 0;

		$rules = [
    		'file.*' => 'required|mimes:pdf,docx,doc|max:204800'
		];
		if ($file < 1 ) {
			$rules['file'] = "required";
		}

		return $rules;
		
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			'file.required'	=> "No File Uploaded.",
			'file.*' => 'Only PDF File are allowed.'
		];
	}
}
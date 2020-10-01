<?php

namespace App\Laravel\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

use Illuminate\Http\Exceptions\HttpResponseException;

class ApiRequestManager extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Override Illuminate\Foundation\Http\FormRequest@response method
     *
     * @return Illuminate\Routing\Redirector
     */

    protected function failedValidation(Validator $validator)
    {
		$format = $this->route('format');
		$_response = [
			'msg' => "Incomplete or invalid input",
			'status' => FALSE,
			'status_code' => "INVALID_DATA",
            'has_requirements' => TRUE,
			'errors' => $validator->errors(),
		];

		switch ($format) {
			case 'json':
				throw new HttpResponseException(response()->json($_response, 422));
			break;
			case 'xml':
				throw new HttpResponseException(response()->xml($_response, 422));
			break;
		}
    }

}

<?php namespace App\Laravel\Requests\Api;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class TransactionRequest extends RequestManager{

	public function rules(){
		$rules = [
            // 'referenceCode' => "required|unique:application,reference_code",
            'referenceCode' => "required",
            'total'         => "required",
            'firstname'  => "nullable",
            'lastname'         => "nullable",
            'subMerchantCode'         => "nullable",
            'subMerchantName'         => "nullable",
            'title'         => "required",
            'emailAddress'         => "nullable",
            'contactNumber'         => "nullable",
            'returnUrl'         => "required",
            'successUrl'         => "required",
            'cancelUrl'         => "required",
            'failedUrl'         => "required",
            'details.particularFee' => "required",
            'details.penaltyFee' => "required",
            'details.dstFee' => "required",
		];

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			'email' => "Invalid email address format.",
            'phone' => "Invalid phone format."
            
		];
	}
}
<?php 
namespace App\Laravel\Events;

use Illuminate\Queue\SerializesModels;
use Mail,Str,Helper,Carbon,Nexmo;

class SendProcessorTransaction extends Event {


	use SerializesModels;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(array $form_data)
	{
		$this->data = $form_data;
		// $this->email = $form_data['insert'];
	}

	public function job(){	
		
		
		foreach($this->data as $index =>$value){
			$phone = $value['contact_number'];
			$ref_num = $value['ref_num'];
			$transaction_code = $value['transaction_code'];
			$processing_fee = $value['processing_fee'];
			$full_name = $value['full_name'];
			$department_name = $value['department_name'];
			$application_name = $value['application_name'];
			$created_at = $value['created_at'];
			$amount = $value['amount'];

			$nexmo = Nexmo::message()->send([
				'to' => '+63'.(int)$phone,
				'from' => 'EOTCPH' ,
				'text' => "Hello " . $full_name . ",\r\nGood day. We are pleased to inform you that your application has been approved by our processor and is now for payment. \r\n\nBelow are your transaction details: \r\nApplication: ".$application_name."\rDepartment: ".$department_name."\rDate: ".$created_at."\r\nFirst Payment:\rPayment Reference Number: ".$ref_num."\rProcessing Fee: ".$processing_fee."\r\nSecond Payment:\rPayment Reference Number: " .$transaction_code."\rApplication Fee: ".$amount."\r\n\nPlease visit the http://54.251.82.120/ and input the payment reference number to the E-Payment section to pay. This payment reference number will expire at 11:59 PM. You can pay via online(Debit/Credit card, e-wallet, etc.) or over-the-counter (7Eleven, Bayad Center, Cebuana Lhuillier, and to other affiliated partners)",
			]);
			
		}
	}
}
